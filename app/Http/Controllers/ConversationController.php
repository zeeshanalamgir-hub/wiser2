<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use App\Models\Job;
use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;

class ConversationController extends Controller
{

    public function __construct(){
        $this->activeTemplate = activeTemplate();
    }

    public function inbox(Request $request)
    {
        if ($request->type == 'Jobs') {
            return searchJob($request);
        } else if ($request->type == 'Professionals') {
            return searchProfessionals($request);
        } else if ($request->type == 'Services') {
            return searchService($request);
        }
        $pageTitle = "Inbox";
        $user = Auth::user();
        // $conversions = Conversation::with('messages')->where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->latest()->get();

        // $messages = $conversions->first()->messages;
        //  dd($conversions->first()->messages);
        //dd($messages->first()->sender->is_freelancer);
        $conversions = Conversation::join('messages','conversations.id','=','messages.conversion_id')
        ->select('conversations.*','messages.read','messages.receiver_id as r_id','messages.id as m_id')
        ->where('conversations.sender_id', $user->id)->orWhere('conversations.receiver_id', $user->id)
        ->groupBy('conversion_id')->orderby('m_id','desc')->latest()->paginate(10);
        //////////////
        // $messages = Message::join('conversations','messages.conversion_id','=','conversations.id')->where('messages.sender_id', $user->id)->orWhere('messages.receiver_id', $user->id)->groupBy('conversion_id')->orderby('messages.id','desc')->first();
        // dd($messages);
        if($user->is_employer == 1 || $user->is_employer == '1')
            {
            $breadcrumbs = [
                ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.buyer.dashboard')],
                ['title' => 'Inbox' ],
            ];
        }
        else
        {
            $breadcrumbs = [
                ['title' => '<i class="fa-solid fa-house fs-14"></i>', 'url' => route('user.home')],
                ['title' => 'Inbox' ],
            ];
        }
        return view($this->activeTemplate . 'user.message.inbox', compact('pageTitle', 'conversions','breadcrumbs'));
    }


    public function chat($conversionId)
    {
        $pageTitle = "Chat List";
        $user = Auth::user();
        $conversions = Conversation::findOrFail($conversionId);
        $job_id = $conversions->job_id;
        $freelancer_id = $conversions->receiver_id;
      //check if job is assign or not
        $checkjob = Job::where('id',$job_id)->get();
        $update = Message::where('conversion_id',$conversions->id)->where('receiver_id',$user->id)->update([
            "read" => 1
        ]);

        $messages = Message::where('conversion_id',$conversions->id)->with('sender', 'receiver')->get();
        return view(activeTemplate() . 'user.message.chat', compact('pageTitle','messages', 'conversionId','job_id','freelancer_id','checkjob'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if($user->id != $request->recevier_id)
        {
        	$request->validate([
        		'subject' => 'required|max:250',
        		'message' => 'required|max:500',
        		'recevier_id' => 'required|exists:users,id',
                'job_id' => 'required'
        	]);

            $conversion = new Conversation();
            $conversion->sender_id = $user->id;
            $conversion->receiver_id = $request->recevier_id;
            $conversion->job_id = $request->job_id;
            $conversion->save();

        	$message = new Message();
            $message->conversion_id = $conversion->id;
        	$message->sender_id = $user->id;
        	$message->receiver_id = $request->recevier_id;
        	$message->subject = $request->subject;
        	$message->message = $request->message;
        	$message->save();
        	$notify[] = ['success', 'Message Sent'];
            return back()->withNotify($notify);
        }
        $notify[] = ['error', "it's You"];
        return back()->withNotify($notify);
    }

    public function messageStore(Request $request)
    {

        $conversionId =Conversation::findOrFail(decrypt($request->conversion_id));
        $receiver =User::findOrFail(decrypt($request->receiver_id));
        if($request->image == null){
            $request->validate(['message' => 'required|max:500']);
        }
        elseif($request->message == null){
            $request->validate(['image' => 'required|mimes:jpeg,jpg,png|max:100000']);
        }
        elseif($request->message && $request->image){
            $request->validate([
                'message' => 'required|max:500',
                'image' => 'required|mimes:jpeg,jpg,png|max:100000'
            ]);
        }
        $message = new Message();
        $message->conversion_id = $conversionId->id;
        $message->sender_id = auth()->user()->id;
        $message->receiver_id = $receiver->id;
        $message->message = $request->message;
        $path = imagePath()['message']['path'];
        $size = imagePath()['message']['size'];
        if($request->hasFile('image')) {
            try {
                $filename = uploadImage($request->image, $path, $size);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $message->file = $filename;
        }
        $message->save();
        $notify[] = ['success', 'Message Sent'];
        return back()->withNotify($notify);
    }
}