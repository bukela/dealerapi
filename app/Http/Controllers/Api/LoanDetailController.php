<?php

namespace App\Http\Controllers\Api;

use App\LoanDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoanDetailRequest;

class LoanDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
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
    public function store(LoanDetailRequest $request)
    {
        $loan = new LoanDetail;

        $loan->application_id = $request->application_id;
        $loan->down_payment = $request->down_payment;
        $loan->trade = $request->trade;
        $loan->payment_frequency = $request->payment_frequency;
        $loan->payment_term = $request->payment_term;
        $loan->amort_term = $request->amort_term;
        $loan->defferal_periods = $request->defferal_periods;
        $loan->amount_owing_at_the_end_of_loan_term = $request->amount_owing_at_the_end_of_loan_term;
        $loan->rate = $request->rate;
        $loan->contract_start_date = $request->contract_start_date;
        $loan->first_payment_date = $request->first_payment_date;
        $loan->payment = $request->payment;

        $loan->save();

        return response([
            'message' => 'Loan details created'
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
    public function update(LoanDetailRequest $request, LoanDetail $loan_detail)
    {
        // $loan_detail->application_id = $request->application_id;
        $loan_detail->down_payment = $request->down_payment;
        $loan_detail->trade = $request->trade;
        $loan_detail->payment_frequency = $request->payment_frequency;
        $loan_detail->payment_term = $request->payment_term;
        $loan_detail->amort_term = $request->amort_term;
        $loan_detail->defferal_periods = $request->defferal_periods;
        $loan_detail->amount_owing_at_the_end_of_loan_term = $request->amount_owing_at_the_end_of_loan_term;
        $loan_detail->rate = $request->rate;
        $loan_detail->contract_start_date = $request->contract_start_date;
        $loan_detail->first_payment_date = $request->first_payment_date;
        $loan_detail->payment = $request->payment;

        $loan_detail->save();

        $loan_detail->filled = true;
        $loan_detail->save();

        $loan_detail->application()->update(['updated' => 1]);


        $log = new LogController;
        if(isset($loan_detail->application->name)) {
            $log->createLog('Loan Detail updated for '.$loan_detail->application->name, 'Loan Detail');
        } else {
            $log->createLog('Loan Detail updated for "name unknown"', 'Loan Detail');
        }

        return response([
            'message' => 'Loan details updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoanDetail $loan_detail)
    {
        $loan_detail->delete();
        return response(['message' => 'Loan detail deleted']);
    }
}
