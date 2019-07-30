<?php

namespace App\Http\Controllers\Api;

use App\Employment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmploymentRequest;

class EmploymentController extends Controller
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
    public function store(EmploymentRequest $request)
    {
        $emp = new Employment;

        $emp->application_id = $request->application_id;
        $emp->employment_status = $request->employment_status;
        $emp->company_name = $request->company_name;
        $emp->company_address = $request->company_address;
        $emp->company_city = $request->company_city;
        $emp->company_province = $request->company_province;
        $emp->company_postal_code = $request->company_postal_code;
        $emp->company_telephone = $request->company_telephone;
        $emp->company_type_of_business = $request->company_type_of_business;
        $emp->company_salary = $request->company_salary;
        $emp->company_salary_type = $request->company_salary_type;
        $emp->company_months_of_employment = $request->company_months_of_employment;
        $emp->company_years_of_employment = $request->company_years_of_employment;
        $emp->company_position = $request->company_position;


        $emp->save();

        return response([
            'message' => 'employment created'
        ]);
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
    public function update(EmploymentRequest $request, $id)
    {
        $emp = Employment::findOrFail($id);

        // $emp->application_id = $request->application_id;
        $emp->employment_status = $request->employment_status;
        $emp->company_name = $request->company_name;
        $emp->company_address = $request->company_address;
        $emp->company_city = $request->company_city;
        $emp->company_province = $request->company_province;
        $emp->company_postal_code = $request->company_postal_code;
        $emp->company_telephone = $request->company_telephone;
        $emp->company_type_of_business = $request->company_type_of_business;
        $emp->company_salary = $request->company_salary;
        $emp->company_salary_type = $request->company_salary_type;
        $emp->company_months_of_employment = $request->company_months_of_employment;
        $emp->company_years_of_employment = $request->company_years_of_employment;
        $emp->company_position = $request->company_position;

        $emp->save();

        $emp->filled = true;
        $emp->save();

        $emp->application()->update(['updated' => 1]);

        $log = new LogController;
        if(isset($emp->application->name)) {
            $log->createLog('Employment updated for '.$emp->application->name, 'Employment');
        } else {
            $log->createLog('Employment updated for "name unknown"', 'Employment');
        }

        return response([
            'message' => 'employment updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employment $employment)
    {
        $employment->delete();

        return response(['message' => 'Employment deleted']);
    }
}
