<?php

namespace App\Http\Controllers\Api;

use App\CoApplicant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CoApplicantRequest;

class CoApplicantController extends Controller
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
    public function store(CoApplicantRequest $request)
    {
        $coappl = new CoApplicant;

        $coappl->application_id = $request->application_id;
        $coappl->business_type = $request->business_type;
        $coappl->first_name = $request->first_name;
        $coappl->last_name = $request->last_name;
        $coappl->sin = $request->sin;
        $coappl->date_of_birth = $request->date_of_birth;
        $coappl->home_phone = $request->home_phone;
        $coappl->mobile_phone = $request->mobile_phone;
        $coappl->email_address = $request->email_address;
        $coappl->street_name = $request->street_name;
        $coappl->appt_po = $request->appt_po;
        $coappl->street_number = $request->street_number;
        $coappl->city = $request->city;
        $coappl->province = $request->province;
        $coappl->postal_code = $request->postal_code;
        $coappl->rent_or_own = $request->rent_or_own;
        $coappl->monthly_housing_payment = $request->monthly_housing_payment;
        $coappl->relationship_to_applicant = $request->relationship_to_applicant;
        $coappl->company_name = $request->company_name;
        $coappl->employer_address = $request->employer_address;
        $coappl->employer_phone = $request->employer_phone;
        $coappl->number_of_years_employed = $request->number_of_years_employed;
        $coappl->number_of_months_employed = $request->number_of_months_employed;
        $coappl->gross_monthly_income = $request->gross_monthly_income;
        $coappl->position = $request->position;

        $coappl->save();
        

        $log = new LogController;
        $log->createLog('Co-applicant created for application id: '.$coappl->application_id, 'Co-Applicant');

        return response(['message' => 'Co applicant created']);
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
    public function update(CoApplicantRequest $request, $id)
    {

        $coappl = CoApplicant::findOrFail($id);

        // $coappl->application_id = $request->application_id;
        $coappl->business_type = $request->business_type;
        $coappl->first_name = $request->first_name;
        $coappl->last_name = $request->last_name;
        $coappl->sin = $request->sin;
        $coappl->date_of_birth = $request->date_of_birth;
        $coappl->home_phone = $request->home_phone;
        $coappl->mobile_phone = $request->mobile_phone;
        $coappl->email_address = $request->email_address;
        $coappl->street_name = $request->street_name;
        $coappl->appt_po = $request->appt_po;
        $coappl->street_number = $request->street_number;
        $coappl->city = $request->city;
        $coappl->province = $request->province;
        $coappl->postal_code = $request->postal_code;
        $coappl->rent_or_own = $request->rent_or_own;
        $coappl->monthly_housing_payment = $request->monthly_housing_payment;
        $coappl->relationship_to_applicant = $request->relationship_to_applicant;
        $coappl->company_name = $request->company_name;
        $coappl->employer_address = $request->employer_address;
        $coappl->employer_phone = $request->employer_phone;
        $coappl->number_of_years_employed = $request->number_of_years_employed;
        $coappl->number_of_months_employed = $request->number_of_months_employed;
        $coappl->gross_monthly_income = $request->gross_monthly_income;
        $coappl->position = $request->position;


        // $data = $request->all();
        $coappl->save();
        $coappl->filled = true;
        $coappl->save();
        // $coappl->fill($data);

        $coappl->application()->update(['updated' => 1]);

        // $app = Application::findOrFail($coappl->application_id);

        $log = new LogController;
        if(isset($coappl->application()->name)) {
            $log->createLog('Co-applicant updated for '.$coappl->application()->name, 'Co-Applicant');
        } else {
            $log->createLog('Co-applicant updated for "name unknown"', 'Co-Applicant');
        }
        

        

        return response(['message' => 'Co applicant updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $coappl = CoApplicant::findOrFail($id);

        $coappl->delete();
        
        return response(['message' => 'Co applicant deleted']);
    }
}
