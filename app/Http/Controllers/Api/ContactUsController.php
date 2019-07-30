<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\ContactUs;
use App\Mail\ContactUsMail;
use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\ContactUsResource;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $contact_us = new ContactUs;

        $receiver = auth('api')->user()->parent_id;
        $receiver_user = User::findOrFail($receiver);
        $contact_us->application_id = $request->application_id;
        $contact_us->user_id = auth()->user()->id;
        $contact_us->sender_name = auth()->user()->name;
        $contact_us->receiver_id = $receiver;
        $contact_us->message = $request->message;
        $contact_us->application_name = $request->app_name;
        $contact_us->phone_number = $request->phone_number;
        $contact_us->email = $request->email;

        if($request->has('emergency_type')) {

            $emergency = $request->emergency_type;
            $data = [];
            foreach($emergency as $eme) {
                $data[] = $eme;
            }
            $contact_us->emergency_type = $data;
        }
  
        $contact_us->save();

        // $receiver = User::findOrFail($request->receiver_id);
        
        if($receiver) {
            Mail::to($receiver_user->email)->send(new ContactUsMail($contact_us));
            return response(['message' => 'contact us created']);
        } else {
            return response(['message' => 'User unknown']);
        }

        

        // Nexmo::message()->send([
        //     // 'to' => $contact_us->phone_number,
        //     'to' => '38166000978',
        //     'from' => $contact_us->sender_name,
        //     'text' => $contact_us->message
        // ]);

        

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ContactUs $contact_us)
    {
        return new ContactUsResource($contact_us);
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
        ContactUs::findOrFail($id)->delete();
    }
}
