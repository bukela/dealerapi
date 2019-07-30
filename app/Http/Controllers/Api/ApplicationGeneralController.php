<?php

namespace App\Http\Controllers\Api;

use App\ApplicationGeneral;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationGeneralRequest;
use App\Http\Resources\ApplicationGeneralResource;
use App\Application;

class ApplicationGeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ApplicationGeneral::all();
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
        $general = new ApplicationGeneral;

        $general->application_id = $request->application_id;
        $general->title = $request->title;
        $general->amount = $request->amount;
        $general->first_name = $request->first_name;
        $general->last_name = $request->last_name;
        $general->sin = $request->sin;
        $general->martial_status = $request->martial_status;
        $general->dependents = $request->dependents;
        $general->date_of_birth = $request->date_of_birth;
        $general->home_phone = $request->home_phone;
        $general->mobile_phone = $request->mobile_phone;
        $general->email_address = $request->email_address;
        $general->prefered_language = $request->prefered_language;
        $general->type_of_government_id = $request->type_of_government_id;
        $general->government_id_provided = $request->government_id_provided;

        $general->save();
        return response([
            'message' => 'Application generals created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ApplicationGeneral $application_general)
    {
        return new ApplicationGeneralResource($application_general);
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
    public function update(ApplicationGeneralRequest $request, ApplicationGeneral $application_general)
    {
        // $application_general->application_id = $request->application_id;
        $application_general->amount = $request->amount;
        $application_general->title = $request->title;
        $application_general->first_name = $request->first_name;
        $application_general->last_name = $request->last_name;
        $application_general->sin = $request->sin;
        $application_general->martial_status = $request->martial_status;
        $application_general->dependents = $request->dependents;
        $application_general->date_of_birth = $request->date_of_birth;
        $application_general->home_phone = $request->home_phone;
        $application_general->mobile_phone = $request->mobile_phone;
        $application_general->email_address = $request->email_address;
        $application_general->prefered_language = $request->prefered_language;
        $application_general->type_of_government_id = $request->type_of_government_id;
        $application_general->government_id_provided = $request->government_id_provided;

        $application_general->save();
        $application_general->filled = true;
        $application_general->save();

        $app = Application::findOrFail($application_general->application_id);
        $app->name = $application_general->first_name.' '.$application_general->last_name;
        $app->first_name = $application_general->first_name;
        $app->last_name = $application_general->last_name;
        $app->amount = $application_general->amount;
        $app->save(); 
        // Application::findOrFail($application_general->application_id)->update(['updated' => 1]);

        $application_general->application()->update(['updated' => 1]);

        $log = new LogController;
        $log->createLog('General information updated for application : '.$application_general->first_name.' '.$application_general->last_name, 'General Information');

        return response([
            'message' => 'Application generals updated'
        ]);
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
