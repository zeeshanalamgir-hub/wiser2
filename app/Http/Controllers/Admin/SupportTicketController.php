<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\SupportAttachment;
use App\Models\SupportMessage;
use App\Models\SupportTicket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SupportTicketController extends Controller
{
    public function tickets()
    {
        $pageTitle = 'Support Tickets';
        $emptyMessage = 'No Data found.';
        $items = SupportTicket::orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle','emptyMessage'));
    }

    public function pendingTicket()
    {
        $pageTitle = 'Pending Tickets';
        $emptyMessage = 'No Data found.';
        $items = SupportTicket::whereIn('status', [0,2])->orderBy('priority', 'DESC')->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle','emptyMessage'));
    }

    public function closedTicket()
    {
        $emptyMessage = 'No Data found.';
        $pageTitle = 'Closed Tickets';
        $items = SupportTicket::where('status',3)->orderBy('id','desc')->with('user')->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle','emptyMessage'));
    }

    public function answeredTicket()
    {
        $pageTitle = 'Answered Tickets';
        $emptyMessage = 'No Data found.';
        $items = SupportTicket::orderBy('id','desc')->with('user')->where('status',1)->paginate(getPaginate());
        return view('admin.support.tickets', compact('items', 'pageTitle','emptyMessage'));
    }

    public function ticketReply($id)
    {
        $ticket = SupportTicket::with('user')->where('id', $id)->firstOrFail();
        $pageTitle = 'Reply Ticket';
        $messages = SupportMessage::with('ticket')->where('supportticket_id', $ticket->id)->orderBy('id','desc')->get();
        return view('admin.support.reply', compact('ticket', 'messages', 'pageTitle'));
    }

    // public function ticketReplySend(Request $request, $id)
    // {
    //     $ticket = SupportTicket::with('user')->where('id', $id)->firstOrFail();
    //     $message = new SupportMessage();
    //     if ($request->replayTicket == 1) {

    //         $attachments = $request->file('attachments');
    //         $allowedExts = array('jpg', 'png', 'jpeg', 'pdf', 'doc', 'docx');

    //         $this->validate($request, [
    //             'attachments' => [
    //                 'max:4096',
    //                 function ($attribute, $value, $fail) use ($attachments, $allowedExts) {
    //                     foreach ($attachments as $attachment) {
    //                         $ext = strtolower($attachment->getClientOriginalExtension());
    //                         if (($attachment->getSize() / 1000000) > 2) {
    //                             return $fail("Miximum 2MB file size allowed!");
    //                         }

    //                         if (!in_array($ext, $allowedExts)) {
    //                             return $fail("Only png, jpg, jpeg, pdf, doc, docx files are allowed");
    //                         }
    //                     }
    //                     if (count($attachments) > 5) {
    //                         return $fail("Maximum 5 files can be uploaded");
    //                     }
    //                 }
    //             ],
    //             'message' => 'required',
    //         ]);
    //         $ticket->status = 1;
    //         $ticket->last_reply = Carbon::now();
    //         $ticket->save();

    //         $message->supportticket_id = $ticket->id;
    //         $message->admin_id = Auth::guard('admin')->id();
    //         $message->message = $request->message;
    //         $message->save();

    //         $path = imagePath()['ticket']['path'];
    //         if ($request->hasFile('attachments')) {
    //             foreach ($request->file('attachments') as $file) {
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

    //         notify($ticket, 'ADMIN_SUPPORT_REPLY', [
    //             'ticket_id' => $ticket->ticket,
    //             'ticket_subject' => $ticket->subject,
    //             'reply' => $request->message,
    //             'link' => route('ticket.view',$ticket->ticket),
    //         ]);

    //         $notify[] = ['success', "Support ticket replied successfully"];

    //     } elseif ($request->replayTicket == 2) {
    //         $ticket->status = 3;
    //         $ticket->save();
    //         $notify[] = ['success', "Support ticket closed successfully"];
    //     }
    //     return back()->withNotify($notify);
    // }

    public function ticketReplySend(Request $request, $id)
    {
        $admin = Admin::first();
        $ticket = SupportTicket::with('user')->where('id', $id)->firstOrFail();

        if ($request->replayTicket == 1) {

            // Proper validation
            $request->validate([
                'attachments' => 'nullable|array|max:5',
                'attachments.*' => 'file|mimes:jpg,png,jpeg,pdf,doc,docx|max:2048',
                'message' => 'required',
            ]);

            // Update ticket
            $ticket->status = 1;
            $ticket->last_reply = now();
            $ticket->save();

            // Save message
            $message = new SupportMessage();
            $message->supportticket_id = $ticket->id;
            $message->admin_id = $admin->id;
            $message->message = $request->message;
            $message->save();

            // Define path (public)
            $path = public_path('assets/support/');

            // Create folder if not exists
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            // Upload files
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    try {

                        // ✅ Safe unique filename
                        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                        $name = Str::slug($name);
                        $ext = $file->getClientOriginalExtension();
                        $fileName = $name . '_' . time() . '.' . $ext;

                        // ✅ Move file
                        $file->move($path, $fileName);

                        // ✅ Save in DB (relative path)
                        $attachment = new SupportAttachment();
                        $attachment->support_message_id = $message->id;
                        $attachment->attachment = 'assets/support/' . $fileName;
                        $attachment->save();

                    } catch (\Exception $exp) {
                        return back()->with('error', 'Could not upload file')->withInput();
                    }
                }
            }

            sendTicketEmailWithNotificationByAdmin($admin->id,$ticket->user_id,$ticket->id,'TICKET_REPLIED');

            return back()->with('success', "Support ticket replied successfully");

        } elseif ($request->replayTicket == 2) {

            $ticket->status = 3;
            $ticket->save();
            sendTicketEmailWithNotificationByAdmin($admin->id,$ticket->user_id,$ticket->id,'TICKET_CLOSED');

            return back()->with('success', "Support ticket closed successfully");
        }
    }

    // public function ticketDownload($ticket_id)
    // {
    //     $attachment = SupportAttachment::findOrFail(decrypt($ticket_id));
    //     $file = $attachment->attachment;


    //     // $path = imagePath()['ticket']['path'];
    //     $path = public_path('assets/support/');

    //     $full_path = $path.'/' . $file;
    //     $title = slug($attachment->supportMessage->ticket->subject).'-'.$file;
    //     $mimetype = mime_content_type($full_path);
    //     header('Content-Disposition: attachment; filename="' . $title);
    //     header("Content-Type: " . $mimetype);
    //     return readfile($full_path);
    // }

    public function ticketDownload($ticket_id)
    {
        $attachment = SupportAttachment::findOrFail(decrypt($ticket_id));

        // Correct full path (DB already has relative path)
        $full_path = public_path($attachment->attachment);

        // Check file exists
        if (!file_exists($full_path)) {
            abort(404, 'File not found');
        }

        // Clean file name
        $fileName = basename($attachment->attachment);
        // Optional: better title
        $title = \Illuminate\Support\Str::slug($attachment->supportMessage->ticket->subject)
            . '-' . $fileName;

        // Proper Laravel download response
        return response()->download($full_path, $title);
    }

    public function ticketDelete(Request $request)
    {
        $message = SupportMessage::findOrFail($request->message_id);
        $path = imagePath()['ticket']['path'];
        if ($message->attachments()->count() > 0) {
            foreach ($message->attachments as $attachment) {
                removeFile($path.'/'.$attachment->attachment);
                $attachment->delete();
            }
        }
        $message->delete();
        $notify[] = ['success', "Delete successfully"];
        return back()->withNotify($notify);

    }

}
