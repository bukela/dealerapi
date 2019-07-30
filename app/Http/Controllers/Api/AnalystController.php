<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class AnalystController extends Controller
{

    // public function __construct() {

    //     $this->middleware('make_merchant');

    // }
    
    public function merchants() {

        return UserResource::collection(User::where('role','merchant')->paginate(10));

    }

    public function brokers() {

        return UserResource::collection(User::where('role','broker')->paginate(10));

    }

    public function analysts() {

        return UserResource::collection(User::where('role','analyst')->paginate(10));

    }
}
