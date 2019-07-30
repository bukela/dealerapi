<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PreviousEmployment;

class PreviousEmploymentController extends Controller
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
        $previous = new PreviousEmployment;

        $previous->application_id = $request->application_id;
        $previous->previous_company_name = $request->previous_company_name;
        $previous->previous_company_address = $request->previous_company_address;
        $previous->previous_company_city = $request->previous_company_city;
        $previous->previous_company_province = $request->previous_company_province;
        $previous->previous_company_postal_code = $request->previous_company_postal_code;
        $previous->previous_company_telephone = $request->previous_company_telephone;
        $previous->previous_company_years_of_employment = $request->previous_company_years_of_employment;

        $previous->save();

        return response(['message' => 'Previous employment created']);
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
        $previous_emp = PreviousEmployment::findOrFail($id);

        // $previous_emp->application_id = $request->application_id;
        $previous_emp->previous_company_name = $request->previous_company_name;
        $previous_emp->previous_company_address = $request->previous_company_address;
        $previous_emp->previous_company_city = $request->previous_company_city;
        $previous_emp->previous_company_province = $request->previous_company_province;
        $previous_emp->previous_company_postal_code = $request->previous_company_postal_code;
        $previous_emp->previous_company_telephone = $request->previous_company_telephone;
        $previous_emp->previous_company_years_of_employment = $request->previous_company_years_of_employment;

        $previous_emp->save();

        $previous_emp->filled = true;
        $previous_emp->save();

        $previous_emp->application()->update(['updated' => 1]);

        $log = new LogController;
        if(isset($previous_emp->application->name)) {
            $log->createLog('Previous Employment updated for '.$previous_emp->application->name, 'Previous Employment');
        } else {
            $log->createLog('Previous Employment updated for "name unknown"', 'Previous Employment');
        }

        return response(['message' => 'Previous employment updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $previous_emp = PreviousEmployment::findOrFail($id);

        return response(['message' => 'Previous employment deleted']);
    }
}
