<?php

namespace App\Http\Controllers;

use App\Events\MessageSentEvent;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getAllUsers()
    {
        $username = auth()->user()->username;
        $user = User::where('username','!=',$username)->get();
        return response()->json($user);
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'sender_id' => 'required',
            'recipient_id' => 'required'
        ]);

        $conv = new Conversation();

        $conv->sender_id = $request->sender_id;
        $conv->recipient_id = $request->recipient_id;
        $conv->message_body = $request->message_body;
        $conv->save();

        broadcast(new MessageSentEvent($conv));

        return response()->json(['message'=>'message sent!']);
    }

    public function chatRoom(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required',
            'sender_id' => 'required'
        ]);

        $conv = Conversation::where('sender_id',$request->sender_id)->orWhere('sender_id',$request->recipient_id)->orWhere('recipient_id',$request->sender_id)->orWhere('recipient_id',$request->recipient_id)->orderBy('created_at','desc')->get();

        if($conv->count() > 0){
            return response()->json($conv);
        }

        return response()->json(['message' => 'empty!']);

    }

    public function deleteMessage($id)
    {
        Conversation::find($id)->delete();

        return response()->json(['message'=>'message delete!']);
    }

    public function deleteConversation(Request $request)
    {

        Conversation::where('sender_id',$request->sender_id)->orWhere('sender_id',$request->recipient_id)->orWhere('recipient_id',$request->sender_id)->orWhere('recipient_id',$request->recipient_id)->delete();

        return response()->json(['message'=>'conversation deleted']);
    }


}
