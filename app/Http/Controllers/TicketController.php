<?php

namespace App\Http\Controllers;

use App\Models\AdminNotification;
use App\Models\SupportAttachment;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TicketController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function supportTicket( Request $request )
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        if (! Auth::user()) {
            abort(404);
        }
        $pageTitle = 'Support Tickets';
        $supports = SupportTicket::where('user_id', Auth::id())->orderBy('priority', 'desc')->orderBy('id', 'desc')->paginate(getPaginate());
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => 'My Tickets'],
        ];

        return view($this->activeTemplate.'user.support.index', compact('supports', 'pageTitle', 'breadcrumbs'));
    }

    public function openSupportTicket(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }

        if (! Auth::user()) {
            abort(404);
        }
        $pageTitle = 'Support Tickets';
        $user = Auth::user();
        $breadcrumbs = [
            ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
            ['title' => ' Create Ticket'],
        ];

        return view($this->activeTemplate.'user.support.create', compact('pageTitle', 'user', 'breadcrumbs'));
    }

    // public function storeSupportTicket(Request $request)
    // {
    //     dd($request->all());
    //     $ticket = new SupportTicket();
    //     $message = new SupportMessage();

    //     $files = $request->file('attachments');
    //     $allowedExts = array('jpg', 'png', 'jpeg', 'pdf','doc','docx');

    //     $this->validate($request, [
    //         'attachments' => [
    //             'max:4096',
    //             function ($attribute, $value, $fail) use ($files, $allowedExts) {
    //                 foreach ($files as $file) {
    //                     $ext = strtolower($file->getClientOriginalExtension());
    //                     if (($file->getSize() / 1000000) > 2) {
    //                         return $fail("Miximum 2MB file size allowed!");
    //                     }
    //                     if (!in_array($ext, $allowedExts)) {
    //                         return $fail("Only png, jpg, jpeg, pdf, doc, docx files are allowed");
    //                     }
    //                 }
    //                 if (count($files) > 5) {
    //                     return $fail("Maximum 5 files can be uploaded");
    //                 }
    //             },
    //         ],
    //         'name' => 'required|max:191',
    //         'email' => 'required|email|max:191',
    //         'subject' => 'required|max:100',
    //         'message' => 'required',
    //         'priority' => 'required|in:1,2,3',
    //     ]);

    //     $user = auth()->user();
    //     $ticket->user_id = $user->id;
    //     $random = rand(100000, 999999);
    //     $ticket->ticket = $random;
    //     $ticket->name = $request->name;
    //     $ticket->email = $request->email;
    //     $ticket->subject = $request->subject;
    //     $ticket->last_reply = Carbon::now();
    //     $ticket->status = 0;
    //     $ticket->priority = $request->priority;
    //     $ticket->save();

    //     $message->supportticket_id = $ticket->id;
    //     $message->message = $request->message;
    //     $message->save();

    //     // $adminNotification = new AdminNotification();
    //     // $adminNotification->user_id = $user->id;
    //     // $adminNotification->title = 'New support ticket has opened';
    //     // $adminNotification->click_url = urlPath('admin.ticket.view',$ticket->id);
    //     // $adminNotification->save();

    //     $path = imagePath()['ticket']['path'];

    //     // Handle attachments safely
    //     if ($request->hasFile('attachments')) {
    //         foreach ($request->file('attachments') as $file) {
    //             try {
    //                 $attachment = new SupportAttachment();
    //                 $attachment->support_message_id = $message->id;
    //                 $attachment->attachment = uploadFile($file, $path);
    //                 $attachment->save();
    //             } catch (\Exception $exp) {
    //                 return back()->with('error', 'Could not upload your file');
    //             }
    //         }
    //     }

    //     $notify[] = ['success', 'ticket created successfully!'];
    //     return redirect()->route('ticket')->withNotify($notify);
    // }

    public function storeSupportTicket(Request $request)
    {
        // Validate first
        $request->validate([
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048', // 2MB per file
            'name' => 'required|max:191',
            'email' => 'required|email|max:191',
            'subject' => 'required|max:100',
            'message' => 'required',
            'priority' => 'required|in:1,2,3',
        ]);

        $user = auth()->user();

        $ticket = new SupportTicket;
        $ticket->user_id = $user->id;
        $ticket->ticket = rand(100000, 999999);
        $ticket->name = $request->name;
        $ticket->email = $request->email;
        $ticket->subject = $request->subject;
        $ticket->last_reply = now();
        $ticket->status = 0;
        $ticket->priority = $request->priority;
        $ticket->save();

        $message = new SupportMessage;
        $message->supportticket_id = $ticket->id;
        $message->message = $request->message;
        $message->save();

        $path = public_path('assets/support/');

        // Ensure directory exists
        if (! File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        // Handle attachments safely
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                try {

                    $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $name = Str::slug($name); // remove spaces & special chars
                    $ext = $file->getClientOriginalExtension();
                    $fileName = $name.'_'.time().'.'.$ext;
                    $file->move($path, $fileName);

                    // Save relative path in DB (same as your current format)
                    $attachment = new SupportAttachment;
                    $attachment->support_message_id = $message->id;
                    $attachment->attachment = 'assets/support/'.$fileName;
                    $attachment->save();

                } catch (\Exception $exp) {
                    return back()->with('error', 'Could not upload your file');
                }
            }
        }

        sendAdminRealTimeNotification(Auth::user(), 'New Ticket Created by '.Auth::user()->username, route('admin.ticket.view', $ticket->id));

        $notify[] = ['success', 'ticket created successfully!'];

        return redirect()->route('ticket')->withNotify($notify);
    }

    public function viewTicket(Request $request, $ticket)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = 'Support Tickets';
        $userId = 0;
        if (Auth::user()) {
            $userId = Auth::id();
        }
        $my_ticket = SupportTicket::where('ticket', $ticket)->where('user_id', $userId)->orderBy('id', 'desc')->firstOrFail();
        $messages = SupportMessage::where('supportticket_id', $my_ticket->id)->orderBy('id', 'desc')->get();
        $attachments = SupportAttachment::whereIn('support_message_id', $messages->pluck('id'))->get()->groupBy('support_message_id');

        $user = auth()->user();
        if ($user) {
            return view($this->activeTemplate.'user.support.view', compact('my_ticket', 'messages', 'pageTitle', 'user', 'attachments'));
        } else {
            return view($this->activeTemplate.'ticket_view', compact('my_ticket', 'messages', 'pageTitle'));
        }

    }

    // public function replyTicket(Request $request, $id)
    // {
    //     $userId = 0;
    //     if (Auth::user()) {
    //         $userId = Auth::id();
    //     }
    //     $ticket = SupportTicket::where('user_id',$userId)->where('id',$id)->firstOrFail();
    //     $message = new SupportMessage();
    //     if ($request->replayTicket == 1) {
    //         $attachments = $request->file('attachments');
    //         $allowedExts = array('jpg', 'png', 'jpeg', 'pdf', 'doc','docx');
    //         $this->validate($request, [
    //             'attachments' => [
    //                 'max:4096',
    //                 function ($attribute, $value, $fail) use ($attachments, $allowedExts) {
    //                     foreach ($attachments as $file) {
    //                         $ext = strtolower($file->getClientOriginalExtension());
    //                         if (($file->getSize() / 1000000) > 2) {
    //                             return $fail("Miximum 2MB file size allowed!");
    //                         }
    //                         if (!in_array($ext, $allowedExts)) {
    //                             return $fail("Only png, jpg, jpeg, pdf doc docx files are allowed");
    //                         }
    //                     }
    //                     if (count($attachments) > 5) {
    //                         return $fail("Maximum 5 files can be uploaded");
    //                     }
    //                 },
    //             ],
    //             'message' => 'required',
    //         ]);

    //         $ticket->status = 2;
    //         $ticket->last_reply = Carbon::now();
    //         $ticket->save();

    //         $message->supportticket_id = $ticket->id;
    //         $message->message = $request->message;
    //         $message->save();

    //         $path = imagePath()['ticket']['path'];

    //         if ($request->hasFile('attachments')) {
    //             foreach ($attachments as $file) {
    //                 try {
    //                     $attachment = new SupportAttachment();
    //                     $attachment->support_message_id = $message->id;
    //                     $attachment->attachment = uploadFile($file, $path);
    //                     $attachment->save();

    //                 } catch (\Exception $exp) {
    //                     $notify[] = ['error', 'Could not upload your ' . $file];
    //                     return back()->withNotify($notify)->withInput();
    //                 }
    //             }
    //         }

    //         $notify[] = ['success', 'Support ticket replied successfully!'];
    //     } elseif ($request->replayTicket == 2) {
    //         $ticket->status = 3;
    //         $ticket->last_reply = Carbon::now();
    //         $ticket->save();
    //         $notify[] = ['success', 'Support ticket closed successfully!'];
    //     }else{
    //         $notify[] = ['error','Invalid request'];
    //     }
    //     return back()->withNotify($notify);

    // }

    public function replyTicket(Request $request, $id)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        
        $userId = auth()->id();

        $ticket = SupportTicket::where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        if ($request->replayTicket == 1) {

            // Proper validation
            $request->validate([
                'attachments' => 'nullable|array|max:5',
                'attachments.*' => 'file|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
                'message' => 'required',
            ]);

            // Update ticket
            $ticket->status = 2;
            $ticket->last_reply = now();
            $ticket->save();

            // Save message
            $message = new SupportMessage;
            $message->supportticket_id = $ticket->id;
            $message->message = $request->message;
            $message->save();

            // Path (public)
            $path = public_path('assets/support/');

            // Ensure folder exists
            if (! File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            // Upload files safely
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    try {

                        // Clean filename
                        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $name = Str::slug($name);
                        $ext = $file->getClientOriginalExtension();
                        $fileName = $name.'_'.time().'.'.$ext;

                        // Move file
                        $file->move($path, $fileName);

                        // Save in DB
                        $attachment = new SupportAttachment;
                        $attachment->support_message_id = $message->id;
                        $attachment->attachment = 'assets/support/'.$fileName;
                        $attachment->save();

                    } catch (\Exception $exp) {
                        return back()->with('error', 'Could not upload file')->withInput();
                    }
                }
            }

            sendAdminRealTimeNotification(Auth::user(), 'Support ticket replied by '.Auth::user()->username, route('admin.ticket.view', $ticket->id));

            $notify[] = ['success', 'Support ticket replied successfully!'];

            return back()->withNotify($notify);

        } elseif ($request->replayTicket == 2) {

            $ticket->status = 3;
            $ticket->last_reply = now();
            $ticket->save();

            sendAdminRealTimeNotification(Auth::user(), 'Support ticket closed by '.Auth::user()->username, route('admin.ticket.view', $ticket->id));

            $notify[] = ['success', 'Support ticket closed successfully!'];

            return back()->withNotify($notify);

        } else {
            $notify[] = ['error', 'Invalid request'];

            return back()->withNotify($notify);
        }
    }

    public function ticketDownload($ticket_id)
    {
        $attachment = SupportAttachment::findOrFail(decrypt($ticket_id));
        $file = $attachment->attachment;

        $path = imagePath()['ticket']['path'];
        $full_path = $path.'/'.$file;

        $title = slug($attachment->supportMessage->ticket->subject);
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $mimetype = mime_content_type($full_path);

        header('Content-Disposition: attachment; filename="'.$title.'.'.$ext.'";');
        header('Content-Type: '.$mimetype);

        return readfile($full_path);
    }
}
