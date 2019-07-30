<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PreviousAddress;

class PreviousAddressController extends Controller
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
        $previous = new PreviousAddress;
        
        $previous->application_id = $request->application_id;
        $previous->address = $request->address;
        $previous->address_2 = $request->address_2;
        $previous->city = $request->city;
        $previous->province = $request->province;
        $previous->postal_code = $request->postal_code;
        $previous->duration_at_current_location = $request->duration_at_current_location;

        $previous->save();

        return response(['message' => 'Previous address created']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $previous = PreviousAddress::findOrFail($id);
        
        // $previous->application_id = $request->application_id;
        $previous->address = $request->address;
        $previous->address_2 = $request->address_2;
        $previous->city = $request->city;
        $previous->province = $request->province;
        $previous->postal_code = $request->postal_code;
        $previous->duration_at_current_location = $request->duration_at_current_location;

        $previous->save();

        $previous->filled = true;
        $previous->save();

        $previous->application()->update(['updated' => 1]);

        $log = new LogController;
        if(isset($previous->application->name)) {
            $log->createLog('Previous Address updated for '.$previous->application->name, 'Previous Address');
        } else {
            $log->createLog('Previous Address updated for "name unknown"', 'Previous Address');
        }

        return response(['message' => 'Previous address updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
