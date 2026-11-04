<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;    

use App\Models\Chat;
use App\Models\User;
use App\Models\Notification;
use App\Events\MyEvent;
use App\Events\MyEventOnline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;
 


class ChatController extends Controller
{

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($sid ,$rid)
    {
        $pageTitle    = "Chat";
        $emptyMessage = "No data found";
        
        $us        = Auth::user();
        $users_ids = $us->ChatUser($us->id);
        // print_r($users_ids); exit;
        $users        = User::whereIn('id',$users_ids)->get();
        $z            = array();
        $user         = Chat::where('ch_s_id',$sid)
                              ->where('ch_r_id',$rid)
                              ->get()->pluck('ch_id');

                    foreach ($user as $key) {
                       array_push($z, $key);    
                    }
                    

        $user1 =Chat::where('ch_s_id',$rid)
                      ->where('ch_r_id',$sid)
                      ->get()->pluck('ch_id');
                     foreach ($user1 as $key) {
                       array_push($z, $key);    
                    }
            //  print_r($z);
            //  exit;
           $chats = Chat::whereIn('ch_id',$z)->get();
           foreach ($chats as $key) {
               
               $ss =User::where('id', $key->ch_s_id)->first();
               $key->sender_user =$ss;
               $rr = User::where('id', $key->ch_r_id)->first();
               $key->reciver_user =$rr;
           } 

        //   print_r($chats);
        //     exit;
        return view($this->activeTemplate . 'chat.index',compact('pageTitle', 'emptyMessage','users','chats','sid','rid')); 
    }

    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
       // print_r($input); exit;

        if($request->input('message')!='' && $request->input('ch_s_id')!=''&& $request->input('ch_r_id')!='')
        {

            if ($request->hasFile('img')) {
                                $files = $request->file('img');
            $filename = $file->getClientOriginalName();
    $extension = $file->getClientOriginalExtension();
    $fileName = str::random(5)."-".date('his')."-".rand(10,10000).".".$extension;
    $destinationPath = 'assets/wiserlance/assets/img/chat'.'/';
    $file->move($destinationPath, $fileName);
        }             

            $data =array( 
                            'ch_s_id'     => $request->input('ch_s_id'),                   
                            'ch_r_id'     => $request->input('ch_r_id'),                  
                            'message'     => $request->input('message'),   
                               
        'image' => $request->file('img')
                        );
                        
                          $res =new MyEvent($data); 
                         event('chanel',$res);
                        $user = Chat::create($data); 
                        if($user){
                             $noti = new Notification();
            $noti->reciever_id = $request->input('ch_r_id');
            $noti->sender_id = $request->input('ch_s_id');
            $noti->type = 'chat';
            $noti->chat_id = $user->id;
            $noti->save();
                        }
                return response()->json(['message'=>'Congratulations your data successfuly update','status'=>1,'result'=>$user]);
        }
           return response()->json(['message'=>'try again you have missing value ','status'=>2]);
     }

  public function is_online($id,$status)
    {

        if($status==='1')
        {  
              user::where('id',$id)->update(['is_login'=>'yes']);
        }
        else if($status==='0')
        {
             user::where('id',$id)->update(['is_login'=>'no']);   
        }
        $zs = user::find($id); 
       
        if($zs)
        {
           $res =  new MyEventOnline($zs); 
           event($res); 
           return response()->json(['message'=>'Congratulations your data successfuly update','status'=>1,'result'=>$zs]);
        }
           return response()->json(['message'=>'try again you have missing value ','status'=>0]);
     }

    /** 
     * Display the specified resource.
     *
     * @param  \App\chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function show($sid , $rid)
    {
        $z= array();
        $user =Chat::where('ch_s_id',$sid)
                    ->where('ch_r_id',$rid)
                    ->get()->pluck('ch_id');

                    foreach ($user as $key) {
                       array_push($z, $key);    
                    }
                    

        $user1 =Chat::where('ch_s_id',$rid)
                    ->where('ch_r_id',$sid)
                     ->get()->pluck('ch_id');
                     foreach ($user1 as $key) {
                       array_push($z, $key);    
                    }
           // print_r($z);
           // exit;
           $res = Chat::whereIn('ch_id',$z)->get();
           foreach ($res as $key) {
               
               $ss =User::select('playerId')->where('id', $key->ch_s_id)->first();
               $key->sender_playerId =$ss['playerId'];
               $rr = User::select('playerId')->where('id', $key->ch_r_id)->first();
               $key->reciver_playerId =$rr['playerId'];
           }
        if($user)
        {
            
                return response()->json(['message'=>'Congratulations ','status'=>1,'result'=>$res]);
        }
           return response()->json(['message'=>'try again you have missing value ','status'=>2]);
    }

      function comparator($object1, $object2) { 
                        return $object1->ch_id > $object2->ch_id; 
                    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function edit(chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function update($sid,  $rid) 
    {
           if($sid && $rid)
           {
                $user = chat::where('ch_s_id',$rid)->where('ch_r_id',$sid)->where('status','false')->update(['status'=>'true']); 
                return response()->json(['message'=>'Congratulations your data successfuly update','status'=>1,'result'=>$user]);
           }
              
        
           return response()->json(['message'=>'try again you have missing value ','status'=>2]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\chat  $chat
     * @return \Illuminate\Http\Response
     */
    public function destroy(chat $chat)
    {
        //
    }
}
