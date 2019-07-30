<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CurrentAddress;
use App\Application;

class CurrentAddressController extends Controller
{
    public function store(Request $request) {

        $address = new CurrentAddress;

        $address->application_id = $request->application_id;
        $address->address = $request->address;
        $address->city = $request->city;
        $address->province = $request->province;
        $address->postal_code = $request->postal_code;
        $address->duration_at_current_location = $request->duration_at_current_location;

        $address->save();

        return response(['message' => 'current address created']);

    }

    public function update(Request $request, $id) {

        $address = CurrentAddress::findOrFail($id);
        
        $address->address = $request->address;
        $address->city = $request->city;
        $address->province = $request->province;
        $address->postal_code = $request->postal_code;
        $address->duration_at_current_location = $request->duration_at_current_location;

        $address->save();
        $address->filled = true;
        $address->save();

        $address->application()->update(['updated' => 1]);

        // $app = Application::findOrFail($address->appliction_id);

        $log = new LogController;
        if(isset($address->application->name)) {
            $log->createLog('Current Address updated for '.$address->application->name, 'Current Address');
        } else {
            $log->createLog('Current Address updated for "name unknown"', 'Current Address');
        }
        
        return response(['message' => 'current address created']);

    }
}
