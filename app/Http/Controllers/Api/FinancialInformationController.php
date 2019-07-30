<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\FinancialInformation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class FinancialInformationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $financial = FinancialInformation::all();
        return $financial;
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
        // $financial = new FinancialInformation;

        // $financial->application_id = $request->application_id;
        // $financial->credit_card_number = $request->credit_card_number;
        // $financial->credit_card_issuer = $request->credit_card_issuer;
        // $financial->declared_bankruptcy = $request->declared_bankruptcy;
        // $financial->date_of_discharge = $request->date_of_discharge;

        // $financial->save;
        // $request->validate([
        //     'credit_card_number' => 'numeric'
        // ]);

        DB::collection('financial_informations')->insert(
            [
                'application_id' => $request->application_id,
                'credit_card_number' => $request->credit_card_number,
                'credit_card_issuer' => $request->credit_card_issuer,
                'declared_bankruptcy' => $request->declared_bankruptcy,
                'date_of_discharge' => $request->date_of_discharge
                
            ]
        );
        return response([
            'message' => 'financial created'
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
        $financial = FinancialInformation::findOrFail($id);
        return $financial;
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
        $financial = FinancialInformation::findOrfail($id);
        // $financial->application_id = $request->application_id;
        $financial->credit_card_number = $request->credit_card_number;
        $financial->credit_card_issuer = $request->credit_card_issuer;
        $financial->declared_bankruptcy = $request->declared_bankruptcy;
        $financial->date_of_discharge = $request->date_of_discharge;

        $financial->save();

        $financial->filled = true;
        $financial->save();

        $financial->application()->update(['updated' => 1]);

        $log = new LogController;
        if(isset($financial->application->name)) {
            $log->createLog('Financial Information updated for '.$financial->application->name, 'Financial Information');
        } else {
            $log->createLog('Financial Information updated for "name unknown"', 'Financial Information');
        }

        return response([
            'message' => 'financial updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(FinancialInformation $financial_information)
    {
        $financial_information->delete();

        return response(['message' => 'financial deleted']);
    }
}
