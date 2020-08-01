<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use App\ChatMessage;
use Illuminate\Support\Str;
class ChatController extends Controller
{
  public function __construct()
  {
    $this->middleware("auth");
  }

  public function startchat(User $user)
  {
    $id=Auth::id();
    $room_id=$this->getRoomId($id,$user->id);

    $messages=ChatMessage::where([
      ['room_id',$room_id],
      ['user_id', $id]
      ])->get();

    return view('chat.chat',['messages'=>$messages,'user'=> $user,'room_id'=> $room_id]);


  }

  private function getRoomId($id,$user_id)
  {
    return ($id > $user_id) ? $user_id.'-'.$id : $id.'-'.$user_id;
  }

  public function sendMessage(Request $request,$id)
  {
    $sender_id=Auth::id();
    $room_id=$this->getRoomId($sender_id,$id);
    $message=$request->input('message');
    $id1=(string)Str::uuid();
    $id2=(string)Str::uuid();

    while ($id1==$id2 || ChatMessage::where('id',$id1)->exists() || ChatMessage::where('id',$id2)->exists())
     {
       $id1=(string)Str::uuid();
       $id2=(string)Str::uuid();
     }

      DB::transaction(
        function() use ($id1,$id2,$id,$sender_id,$room_id,$message)
        {

          ChatMessage::create([
            'id'=> $id1,
            'user_id' => $sender_id,
            'room_id' => $room_id,
            'sender_id' => $sender_id,
            'message' => $message
          ]);

          ChatMessage::create([
            'id' => $id2,
            'user_id' => $id,
            'room_id' => $room_id,
            'sender_id' => $sender_id,
            'message' => $message
          ]);


        }
      );

      broadcast(new \App\Events\newMessage($message,$room_id))->toOthers();



    return [$message];
  }




}
