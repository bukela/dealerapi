<?php

namespace App\Http\Controllers\Api;

use App\Chat;
use App\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MessageResource;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // $user = auth('api')->user()->id;
        
        // return Message::all()->pluck('body');
        return MessageResource::collection(Message::orderBy('created_at', 'desc')->get());
        // return Message::where('sender_id', $user)->orWhere('receiver_id', $user)->pluck('body');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = new Message;
        
        $message->body = $request->body;
        $message->sender_id = auth('api')->user()->id;
        // $message->sender_id = "5c9cacfa2cac28f90960cf95";
        // $message->receiver_id = $request->receiver_id;
        $message->receiver_id = '5c6d384ef88fc60e90396102';

        $message->save();
        $chat = Chat::where('users' ,$message->sender_id)->where('users' ,$message->receiver_id)->get();

        if(count($chat) < 1) {

            $chatty = new Chat;
            // dd($chatty);
            // $chatty->receiver_id = $message->receiver_id;
            // $chatty->sender_id = $message->sender_id;
            $chatty->push('users',[$message->sender_id,$message->receiver_id]);
            $chatty->save();
            $message->chat_id = $chatty->id;
            $message->save();

        } else {

            $message->chat_id = $chat->first()->id;
            $message->save();

        }
        
        
        broadcast(new MessageSent($message))->toOthers();
       
        return response(['message' => 'message sent']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $message = Message::findOrFail($id);
        return new MessageResource($message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $message = Message::findOrFail($id);

        $message->delete();

        return response(['message' => 'message deleted']);
    }
}
