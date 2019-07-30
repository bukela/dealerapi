<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Http\Resources\AppDocumentsResource;

class DocumentController extends Controller
{

    // public function __construct() {

    //     $this->middleware('auth:api');
    //     $this->middleware('analyst')->only('merchant_documents');
    // }

    public function app_docs($id) {

        $app = Application::findOrFail($id);
        return $app->files;

    }

    public function merchant_documents(Request $request) {

        $search = $request->get('search');
        
        $terms = explode(',', $search);
        

        $user = auth('api')->user()->id;

        $apps = Application::where('user_id', $user)->with('files')->has('files')->paginate(10);
        
        if(isset($search)) {

            $apps = Application::where('user_id', $user)->whereHas('files', function ($query) use ($terms) {
                $query->whereIn('file_type_code',$terms);
            })->paginate(10);
            return AppDocumentsResource::collection($apps);

        } else {

            return AppDocumentsResource::collection($apps);

        }
        
    }

    public function super_documents(Request $request) {

        $search = $request->get('search');
        
        $terms = explode(',', $search);
        

        // $user = auth('api')->user()->id;

        $apps = Application::with('files')->has('files')->paginate(10);
        
        if(isset($search)) {

            $apps = Application::whereHas('files', function ($query) use ($terms) {
                $query->whereIn('file_type_code',$terms);
            })->paginate(10);
            return AppDocumentsResource::collection($apps);

        } else {

            return AppDocumentsResource::collection($apps);

        }
        
    }

    public function broker_documents(Request $request) {

        $search = $request->get('search');
        
        $terms = explode(',', $search);
        
        $user = auth('api')->user()->id;

        $merchants_id = User::where('parent_id', $user)->pluck('_id')->toArray();
        array_push($merchants_id,$user);

        // $user_apps = Application::whereIn('user_id', $merchants_id)->get();

        $apps = Application::whereIn('user_id', $merchants_id)->with('files')->has('files')->paginate(10);
        
        if(isset($search)) {

            $apps = Application::whereIn('user_id', $merchants_id)->whereHas('files', function ($query) use ($terms) {
                $query->whereIn('file_type_code',$terms);
            })->paginate(10);
            return AppDocumentsResource::collection($apps);

        } else {

            return AppDocumentsResource::collection($apps);

        }
        
    }
}
