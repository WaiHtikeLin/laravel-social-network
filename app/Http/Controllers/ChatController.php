<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\ChatMessage;
use App\Room;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
class ChatController extends Controller
{
  public function __construct()
  {
    $this->middleware("auth");
  }

  public function index()
  {
    return view('menu.messages');
  }

  public function getLatestMessages()
  { $id=Auth::id();
    $rooms=Room::select('id')->whereHas('messages',function($query) use ($id){
      $query->where('user_id',$id);
    })->with(['messages'=>function($query) use ($id){
      $query->where('user_id',$id)->latest()->limit(5);
    },'members'=>function($query) use ($id){
      $query->with(['pics'=>function($query){
        $query->where([['type','profile'],['status',1]]);
      }])->where('id','<>',$id);
    }])->withCount(['messages'=> function($query) use ($id){
      $query->where([
        ['user_id',$id],
        ['sender_id','<>',$id],
        ['seen',0]
      ]);
    }])->orderBy('updated_at','desc')->limit(5)->get();

    return $rooms->map(function($room){

      $room->messages[0]->message=Crypt::decryptString($room->messages[0]->message);
      return $room;
    });

  }

  public function getAllMessages($page)
  { $id=Auth::id();
    $rooms=Room::select('id')->whereHas('messages',function($query) use ($id){
      $query->where('user_id',$id);
    })->with(['messages'=>function($query) use ($id){
      $query->where('user_id',$id)->latest()->limit(10);
    },'members'=>function($query) use ($id){
      $query->with(['pics'=>function($query){
        $query->where([['type','profile'],['status',1]]);
      }])->where('id','<>',$id);
    }])->withCount(['messages'=> function($query) use ($id){
      $query->where([
        ['user_id',$id],
        ['sender_id','<>',$id],
        ['seen',0]
      ]);
    }])->orderBy('updated_at','desc')->offset($page*10)->limit(10)->get();

    return $rooms->map(function($room){

      $room->messages[0]->message=Crypt::decryptString($room->messages[0]->message);

      return $room;
    });

  }



  public function countUnseenMessages()
  {
    $id=Auth::id();

    return [Room::whereHas('messages', function($query) use ($id){
      $query->where([
      ['seen',0],
      ['user_id',$id],
      ['sender_id','<>',$id]]);
    })->count()];

  }

  public function removeChat($room_id)
  {
    ChatMessage::where([
      ['room_id',$room_id],
      ['user_id',Auth::id()]
    ])->delete();
  }

  public function startchat(User $recipient)
  {
    $id=Auth::id();
    $room_id=$this->getRoomId($id,$recipient->id);

    if(Room::where('id',$room_id)->doesntExist())
      {
        $room=new Room;
        $room->id=$room_id;
        $room->save();

        $room->members()->attach([$id,$recipient->id]);
      }



    return view('chat.chat',['user'=> $recipient,'profile_pic'=>$recipient->getProfilePic(),
    'room_id'=> $room_id]);


  }

  public function getMessages(User $recipient,$index)
  {
    $my_id=Auth::id();
    $room_id=$this->getRoomId($my_id,$recipient->id);

    ChatMessage::where([
      ['room_id',$room_id],
      ['user_id',$my_id],
      ['seen',0]
      ])->update(['seen'=>1]);

      event(new \App\Events\sawMessage($room_id, $recipient->id));

      return ChatMessage::where([
      ['room_id',$room_id],
      ['user_id', $my_id]
      ])->latest()->offset($index*20)->limit(20)->cursor()->map(function($msg){
        $msg->message=Crypt::decryptString($msg->message);
        return $msg;
      });
  }

  public function updateMessage(Request $request,$id)
  { $user_id=Auth::id();
    $msg=ChatMessage::where([
      ['id',$id],
      ['user_id','<>',$user_id]])->first();

    if($user_id!=$msg->sender_id)
      return ['error'];

    $message=$request->input('message');
    ChatMessage::where('id', $id)->update(['message'=>Crypt::encryptString($message) ]);

    event(new \App\Events\editedMessage($id,$message,$msg->room_id,$msg->user_id));

    return [$message];
  }

  public function deleteMessage($id)
  {
    $user_id=Auth::id();

    $sender_id=ChatMessage::where('id',$id)->limit(1)->value('sender_id');
    if($user_id!=$sender_id)
      return ['error'];

    ChatMessage::where([
      ['id', $id],
      ['user_id', $user_id]
    ])->delete();

    return ['ok'];
  }

  private function getRoomId($id,$user_id)
  {
    return ($id > $user_id) ? $user_id.'-'.$id : $id.'-'.$user_id;
  }

  public function seeMessage($id)
  {
    ChatMessage::where([
      ['id', $id],
      ['seen',0]
      ])->update(['seen'=> 1]);

    $message=ChatMessage::findOrFail($id);

    event(new \App\Events\sawMessage($message->room_id, $message->sender_id));
  }

  public function sendMessage(Request $request,User $recipient)
  { $id=$recipient->id;
    $sender=Auth::user();
    $room_id=$this->getRoomId($sender->id,$id);
    $msg=$request->input('message');
    $encrypted=Crypt::encryptString($msg);


    $msg_id=(string)Str::uuid();

    while (ChatMessage::where('id',$msg_id)->exists())
     {
       $msg_id=(string)Str::uuid();
     }


     $message=[
       'id' => $msg_id,
       'user_id' => $id,
       'room_id' => $room_id,
       'sender_id' => $sender->id,
       'message' => $encrypted,
       'seen' => 0
     ];
          $mymessage=ChatMessage::create([
            'id'=> $msg_id,
            'user_id' => $sender->id,
            'room_id' => $room_id,
            'sender_id' => $sender->id,
            'message' => $encrypted,
            'seen' => 0
          ]);

          ChatMessage::create($message);

          Room::where('id',$room_id)->update(['updated_at'=>now()]);


    $count=Room::whereHas('messages', function($query) use ($id){
      $query->where([['seen',0],['user_id',$id]]);
    })->count();

    $mymessage->message=$msg;
    $message['message']=$msg;
    $message['created_at']=$mymessage->created_at;

    event(new \App\Events\newMessage($message,$sender,$sender->getProfilePic(),$count,$id));



    return $mymessage;
  }




}
