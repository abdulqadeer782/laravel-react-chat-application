<?php

namespace App\Http\Controllers;

use App\Events\MessageSentEvent;
use App\Models\Conversation;
use App\Models\Message;
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
            'to' => 'required',
            'from' => 'required'
        ]);

        $conv = new Message();

        $conv->to = $request->to;
        $conv->from = auth()->user()->id;
        $conv->message = $request->message;
        $conv->save();

        broadcast(new MessageSentEvent($conv));

        return response()->json(['message'=>'message sent!','message'=>$conv]);
    }

    public function chatRoom(Request $request)
    {
        $request->validate([
            'to' => 'required',
            'from' => 'required'
        ]);

        $conv = Conversation::where('to',$request->to)->orWhere('from',$request->from)->orWhere('from',$request->to)->orWhere('from',$request->from)->orderBy('created_at','desc')->get();

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

        Conversation::where('to',$request->to)->orWhere('to',$request->from)->orWhere('from',$request->to)->orWhere('from',$request->from)->delete();

        return response()->json(['message'=>'conversation deleted']);
    }


}
