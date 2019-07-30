<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Logger;
use App\Http\Resources\LoggerResource;

class FileLogController extends Controller
{

    public function index() {

        return LoggerResource::collection(Logger::orderBy('created_at', 'desc')->get());

    }
    
    public function createLog($desc, $model, $model_id) {

        $log = new Logger;
        $log->description = $desc;
        $log->username = auth('api')->user()->name;
        $log->model = $model;
        $log->model_id = $model_id;
        $log->save();

    }

    public function destroy() {

        Logger::truncate();

        return response(['message' => 'logs deleted']);

    }


}
