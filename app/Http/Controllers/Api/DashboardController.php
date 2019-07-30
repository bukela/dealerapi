<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\ContactUs;
use App\Application;
use App\CurrentAddress;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\ContactUsResource;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationDetailsResource;
use App\Http\Resources\ApplicationCompleteResource;

class DashboardController extends Controller
{
    public function user_applications() {

        $user = auth('api')->user();

        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'about_equipment', 'coapplicant', 'previous_address', 'home_own','files','current_address'];
        
        return Application::with($rel)->where('updated', 1)->where('user_id', $user->id)->paginate(1000);
        

    }

    public function started() {

        $user = auth('api')->user();

        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'about_equipment', 'coapplicant', 'previous_address', 'home_own','files','current_address'];
        
        return Application::with($rel)->where('updated', 1)->where('user_id', $user->id)->where('status','started')->get();
        

    }

    public function analyst_started() {

        // $user = auth('api')->user();

        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'about_equipment', 'coapplicant', 'previous_address', 'home_own','files','current_address'];
        
        return Application::with($rel)->where('updated', 1)->where('status','started')->get();
        

    }

    public function analyst_applications() {


        $all_applications = Application::where('updated', 1)->get();

        $started = ApplicationResource::collection($all_applications->where('status', 'started')->sortByDesc('created_at'));
        $submitted = ApplicationResource::collection($all_applications->where('status', 'submitted')->sortByDesc('created_at'));
        $completed = ApplicationResource::collection($all_applications->where('status', 'completed')->sortByDesc('created_at'));
        $approved = ApplicationResource::collection($all_applications->where('status', 'approved')->sortByDesc('created_at'));
        $all_apps = ApplicationResource::collection($all_applications->sortByDesc('created_at'));

        $user = auth()->user()->id;

        $messages = ContactUsResource::collection(ContactUs::where('receiver_id', $user)->get());

        return response([
            'started' => $started,
            'count_started' => count($started),
            'submitted' => $submitted,
            'count_submitted' => count($submitted),
            'completed' => $completed,
            'count_completed' => count($completed),
            'approved' => $approved,
            'count_approved' => count($approved),
            'all_apps' => $all_apps,
            'messages' => $messages,
        ]);

        

    }

    public function merchant_applications() {

        $user = auth('api')->user()->id;

        $all_applications = Application::where('user_id', $user)->where('updated', 1)->get();
        
        // $started = ApplicationResource::collection($all_applications->where('status', 'started')->sortByDesc('created_at'));
        // $submitted = ApplicationResource::collection($all_applications->where('status', 'submitted')->sortByDesc('created_at'));
        // $completed = ApplicationResource::collection($all_applications->where('status', 'completed')->sortByDesc('created_at'));
        // $approved = ApplicationResource::collection($all_applications->where('status', 'approved')->sortByDesc('created_at'));
        // $all_apps = ApplicationResource::collection($all_applications->sortByDesc('created_at'));
        $all_apps = ApplicationDetailsResource::collection($all_applications->sortByDesc('created_at'));

        return response([
            // 'started' => $started,
            // 'count_started' => count($started),
            // 'submitted' => $submitted,
            // 'count_submitted' => count($submitted),
            // 'completed' => $completed,
            // 'count_completed' => count($completed),
            // 'approved' => $approved,
            // 'count_approved' => count($approved),
            'applications' => $all_apps,
        ]);

        

    }

    public function broker_applications() {

        $user = auth('api')->user()->id;
        $merchants_id = User::where('parent_id', $user)->pluck('_id')->toArray();
        array_push($merchants_id,$user);

        $all_applications = Application::whereIn('user_id', $merchants_id)->where('updated', 1)->get();

        // $started = ApplicationResource::collection($all_applications->where('status', 'started')->sortByDesc('created_at'));
        // $submitted = ApplicationResource::collection($all_applications->where('status', 'submitted')->sortByDesc('created_at'));
        // $completed = ApplicationResource::collection($all_applications->where('status', 'completed')->sortByDesc('created_at'));
        // $approved = ApplicationResource::collection($all_applications->where('status', 'approved')->sortByDesc('created_at'));
        $all_apps = ApplicationResource::collection($all_applications->sortByDesc('created_at'));

        
        $merchants = UserResource::collection(User::where('parent_id', $user)->get());

        return response([
            // 'started' => $started,
            // 'count_started' => count($started),
            // 'submitted' => $submitted,
            // 'count_submitted' => count($submitted),
            // 'completed' => $completed,
            // 'count_completed' => count($completed),
            // 'approved' => $approved,
            // 'count_approved' => count($approved),
            'merchants' => $merchants,
            'applications' => $all_apps,
        ]);

    }

    public function super_applications() {

        $all_applications = Application::where('updated', 1)->get();
        
        $started = ApplicationResource::collection($all_applications->where('status', 'started')->sortByDesc('created_at'));
        $submitted = ApplicationResource::collection($all_applications->where('status', 'submitted')->sortByDesc('created_at'));
        $completed = ApplicationResource::collection($all_applications->where('status', 'completed')->sortByDesc('created_at'));
        $approved = ApplicationResource::collection($all_applications->where('status', 'approved')->sortByDesc('created_at'));
        $all_apps = ApplicationResource::collection($all_applications->sortByDesc('created_at'));

        $users = User::all();
        $merchants = UserResource::collection($users->where('role', 'merchant'));
        $brokers = UserResource::collection($users->where('role', 'broker'));
        $analysts = UserResource::collection($users->where('role', 'analyst'));
        $credit_analysts = UserResource::collection($users->where('role', 'credit_analyst'));

        $user = auth()->user()->id;

        $messages = ContactUsResource::collection(ContactUs::where('receiver_id', $user)->get());

        return response([
            'started' => $started,
            'count_started' => count($started),
            'submitted' => $submitted,
            'count_submitted' => count($submitted),
            'completed' => $completed,
            'count_completed' => count($completed),
            'approved' => $approved,
            'count_approved' => count($approved),
            'all_apps' => $all_apps,
            'messages' => $messages,
            'merchants' => $merchants,
            'brokers' => $brokers,
            'analysts' => $analysts,
            'credit_analysts' => $credit_analysts,
        ]);

    }

    public function submitted() {

        // $user = auth('api')->user();

        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'about_equipment', 'coapplicant', 'previous_address', 'home_own','files','current_address'];
        
        // return Application::with($rel)->where('updated', 1)->where('user_id', $user->id)->where('status','submitted')->paginate(100);
        return ApplicationResource::collection(Application::where('updated', 1)->where('status','submitted')->paginate(100));
        

    }
    public function approved() {

        $user = auth('api')->user();

        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'about_equipment', 'coapplicant', 'previous_address', 'home_own','files','current_address'];
        
        return Application::with($rel)->where('updated', 1)->where('user_id', $user->id)->where('status','approved')->paginate(100);
        

    }
    public function completed() {

        $user = auth('api')->user();

        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'about_equipment', 'coapplicant', 'previous_address', 'home_own','files','current_address'];
        
        return Application::with($rel)->where('updated', 1)->where('user_id', $user->id)->where('status','completed')->paginate(100);
        

    }


    public function alla() {

        return ApplicationCompleteResource::collection(Application::all());

    }
}
