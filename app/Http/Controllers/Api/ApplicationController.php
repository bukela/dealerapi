<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Application;
use App\File as AppFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\ApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationDetailsResource;

class ApplicationController extends Controller
{

    // public function __construct() {

    //     $this->middleware('create-application')->only('store');
    //     $this->middleware('edit-application')->only('update','destroy');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'coapplicant', 'previous_address', 'home_own','files', 'about_equipment', 'current_address'];
        
        return ApplicationResource::collection(Application::paginate(10));

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
    public function store(ApplicationRequest $request)
    {
        $application = new Application;

        $application->user_id = auth('api')->user()->id;
        $application->type = $request->type;
        $application->status = $request->status;
        $application->sample_app = $request->sample_app;

        $application->save();
        
        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'coapplicant', 'previous_address', 'home_own', 'about_equipment', 'current_address'];
        
        foreach($rel as $r) {

            $application->$r()->create(['application_id' => $application->id]);
            $data[] = [$r => $application->$r->id];

        }

        // $log = new LogController;
        // $log->createLog('Application with id: '.$application->id.', created', 'Application');

        return response([
            'message' => 'application created',
            'data' => $data,
            'app_id' => $application->id
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show(Application $application)
    {
        // return new ApplicationResource($application);
        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'coapplicant', 'previous_address', 'home_own', 'files', 'about_equipment'];
        // return new ApplicationDetailsResource($application);
        return new ApplicationResource($application);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        // $application->name = $request->name;
        // $application->amount = $request->amount;
        $application->type = $request->type;
        $application->status = $request->status;
        $application->sample_app = $request->sample_app;

        $application->save();

        $log = new LogController;
        $log->createLog('Application with id: '.$application->id.', updated', 'Application');

        return response([
            'message' => 'application updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'coapplicant', 'previous_address', 'home_own', 'about_equipment'];

        foreach($rel as $r) {
            
            if(!empty($application->$r)) {

                $application->$r->delete();

            }

        }

        if (isset($application->files)) {
            foreach($application->files as $file) {

                File::delete(public_path('uploads/documents/'.$file->filename));
                $file->delete();
            }
        }

        $application->delete();

        $log = new LogController;
        $log->createLog('Application with id: '.$application->id.', deleted', 'Application');

        return response([
            'message' => 'Application deleted'
        ]);
    }

    public function non_updated() {

        // $day_ago = Carbon::now()->subDays(1)->toDateTimeString();
        $day_ago = Carbon::now()->startOfDay();

        $application = Application::where('created_at', '<', $day_ago)->where('updated', null)->get();

        // dd($application->count());

        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'coapplicant', 'previous_address', 'home_own', 'about_equipment'];

        foreach($application as $app) {

            foreach($rel as $r) {
            
                if(!empty($app->$r)) {
    
                    $app->$r->delete();
    
                }
                $app->delete();
            }
            

        }

        return response(['applications deleted']);
        
        // $app->delete();
    }

    public function purge() {

        \App\CoApplicant::where('filled' ,'!=', true)->delete();
        \App\CurrentAddress::where('filled' ,'!=', true)->delete();
        \App\ApplicationGeneral::where('filled' ,'!=', true)->delete();
        \App\Employment::where('filled' ,'!=', true)->delete();
        \App\FinancialInformation::where('filled' ,'!=', true)->delete();
        \App\HomeOwnershipDetail::where('filled' ,'!=', true)->delete();
        \App\LoanDetail::where('filled' ,'!=', true)->delete();
        \App\PreviousAddress::where('filled' ,'!=', true)->delete();
        \App\PreviousEmployment::where('filled' ,'!=', true)->delete();

        return response(['deleted']);

    }
}
