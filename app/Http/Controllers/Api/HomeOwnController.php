<?php

namespace App\Http\Controllers\Api;

use App\HomeOwnershipDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\HomeOwnershipRequest;

class HomeOwnController extends Controller
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
    public function store(HomeOwnershipRequest $request)
    {
        $home_own = new HomeOwnershipDetail;

        $home_own->application_id = $request->application_id;
        $home_own->residential_status = $request->residential_status;
        $home_own->title_of_property = $request->title_of_property;
        $home_own->monthly_payment = $request->monthly_payment;
        $home_own->payment_made_to = $request->payment_made_to;
        $home_own->outstanding_mortgage_balance = $request->outstanding_mortgage_balance;

        $home_own->save();
        return response(['message' => 'Home ownership created']);
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
    public function update(HomeOwnershipRequest $request, $id)
    {
        $home_own = HomeOwnershipDetail::findOrFail($id);

        // $home_own->application_id = $request->application_id;
        $home_own->residential_status = $request->residential_status;
        $home_own->title_of_property = $request->title_of_property;
        $home_own->monthly_payment = $request->monthly_payment;
        $home_own->payment_made_to = $request->payment_made_to;
        $home_own->outstanding_mortgage_balance = $request->outstanding_mortgage_balance;

        $home_own->save();

        $home_own->filled = true;
        $home_own->save();

        $home_own->application()->update(['updated' => 1]);

        $log = new LogController;
        if(isset($home_own->application->name)) {
            $log->createLog('Home Ownership updated for '.$home_own->application->name, 'Home Ownership');
        } else {
            $log->createLog('Home Ownership updated for "name unknown"', 'Home Ownership');
        }

        return response(['message' => 'Home ownership updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $home_own = HomeOwnershipDetail::findOrFail($id);

        $home_own->delete();
        return response(['message' => 'Home ownership deleted']);

    }
}
