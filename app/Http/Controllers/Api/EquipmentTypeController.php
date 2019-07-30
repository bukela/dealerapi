<?php

namespace App\Http\Controllers\Api;

use App\EquipmentType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EquipmentTypeController extends Controller
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
        $about_equipment = new EquipmentType;

        $about_equipment->application_id = $request->application_id;
        $about_equipment->program = $request->program;
        $about_equipment->class = $request->class;
        $about_equipment->type = $request->type;
        $about_equipment->description = $request->description;
        $about_equipment->vehicle_year = $request->vehicle_year;
        $about_equipment->vehicle_odometer = $request->vehicle_odometer;
        $about_equipment->serial_number = $request->serial_number;
        $about_equipment->equipment_cost = $request->equipment_cost;
        $about_equipment->merchant = $request->merchant;
        $about_equipment->merchant_rep = $request->merchant_rep;
        $about_equipment->data_entry = $request->data_entry;

        $about_equipment->save();

        
        // EquipmentType::create($request->all());

        return response(['message' => 'about equipment created']);
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
        $about_equipment = EquipmentType::findOrFail($id);

        // $about_equipment->application_id = $request->application_id;
        $about_equipment->program = $request->program;
        $about_equipment->class = $request->class;
        $about_equipment->type = $request->type;
        $about_equipment->description = $request->description;
        $about_equipment->vehicle_year = $request->vehicle_year;
        $about_equipment->vehicle_odometer = $request->vehicle_odometer;
        $about_equipment->serial_number = $request->serial_number;
        $about_equipment->equipment_cost = $request->equipment_cost;
        $about_equipment->merchant = $request->merchant;
        $about_equipment->merchant_rep = $request->merchant_rep;
        $about_equipment->data_entry = $request->data_entry;

        $about_equipment->save();

        $about_equipment->filled = true;
        $about_equipment->save();

        $about_equipment->application()->update(['updated' => 1]);


        $log = new LogController;
        if(isset($about_equipment->application->name)) {
            $log->createLog('Equipment Type updated for '.$about_equipment->application->name, 'Equipment Type');
        } else {
            $log->createLog('Equipment Type updated for "name unknown"', 'Equipment Type');
        }

        return response(['message' => 'about equipment updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $about_equipment = EquipmentType::findOrFail($id);
        $about_equipment->delete();

        return response(['message' => 'about equipment deleted']);
    }
}
