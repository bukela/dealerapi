<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Permission;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserSuperResource;
use Symfony\Component\HttpFoundation\Response;
use function GuzzleHttp\json_decode;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // public function __construct() {

    //     $this->middleware('make_merchant');

    // }


    public function index(Request $request)
    {

        $search = $request->search;

        if(!empty($search)) {

            return UserSuperResource::collection(User::where('name', 'like', "%{$search}%")->
            orWhere('email', 'like', "%{$search}%")->paginate(100));

        } else {

            return UserSuperResource::collection(User::paginate(100));

        }
          
       
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
    public function store(UserRequest $request)
    {
        {
    
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            $user->phone = $request->phone;
            // $user->parent_id = auth('api')->user()->id;
            isset($request->parent_id) ? $user->parent_id = $request->parent_id : $user->parent_id = auth('api')->user()->id;
            // $user->parent_id = $request->parent_id;
            $user->password = bcrypt($request->password);
    
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatar_name = uniqid('avatar_') . '.' . $avatar->getClientOriginalExtension();
                $path = public_path('/uploads/avatars');
                $avatar->move($path, $avatar_name);
                
                $user->avatar = $avatar_name;
            }
    // dd($request->permissions);
            $user->save();

            $merchant_basic = [
                'create-application',
                'save-application',
                // 'edit-application',
                // 'view-application',
                'submit-application',
                'create-new-contact',
                'assign-contact-to-current-application',
                'convert-contact-to-new-application',
                // 'upload-and-replace-documents'
            ];

            $broker_basic = [
                // 'create-application',
                // 'save-application',
                // 'edit-application',
                'view-application',
                // 'submit-application',
                // 'create-new-contact',
                // 'assign-contact-to-current-application',
                // 'convert-contact-to-new-application',
                // 'upload-and-replace-documents',
                'view-all-merchant-information',
                'edit-merchant-information',
                'create-new-merchant-account',
                'change-terms'
            ];

            $analyst_basic = [
                // 'create-application',
                'save-application',
                'edit-application',
                'view-application',
                'submit-application',
                'create-new-contact',
                'assign-contact-to-current-application',
                'convert-contact-to-new-application',
                'upload-and-replace-documents',
                // 'view-all-merchant-information',
                // 'edit-merchant-information',
                // 'create-new-merchant-account',
                // 'change-terms',
                'access-to-underwriting',
                'access-to-servicing',
                'access-to-collections',
                'access-archives'
            ];

            $credit_analyst_basic = [
                // 'create-application',
                'save-application',
                'edit-application',
                'view-application',
                'submit-application',
                'create-new-contact',
                'assign-contact-to-current-application',
                'convert-contact-to-new-application',
                'upload-and-replace-documents',
                // 'view-all-merchant-information',
                // 'edit-merchant-information',
                // 'create-new-merchant-account',
                // 'change-terms',
                'access-to-underwriting',
                'access-archives'
            ];

            if($user->role == 'merchant') {
                $user->push('permissions', $merchant_basic);
            };

            if($user->role == 'broker') {
                $user->push('permissions', $broker_basic);
            };

            if($user->role == 'analyst') {
                $user->push('permissions', $analyst_basic);
            };

            if($user->role == 'credit_analyst') {
                $user->push('permissions', $credit_analyst_basic);
            };

            //adding permissions
            if(isset($request->permissions)) {

                $permi = $request->permissions;
                $user->permissions_edit = $request->permissions;
                $user->permissions = [];
                $user->save();

                if($user->role == 'merchant') {

                    // $all = array_merge($merchant_basic, $request->permissions);
                    $all = array_merge($merchant_basic, $permi);
                    $user->push('permissions', $all);

                }

                if($user->role == 'broker') {

                    // $all = array_merge($broker_basic, $request->permissions);
                    $all = array_merge($broker_basic, $permi);
                    $user->push('permissions', $all);
                }

                if($user->role == 'analyst') {
                    
                    // $all = array_merge($analyst_basic, $request->permissions);
                    $all = array_merge($analyst_basic, $permi);
                    $user->push('permissions', $all);
                }

                if($user->role == 'credit_analyst') {
                    
                    // $all = array_merge($credit_analyst_basic, $request->permissions);
                    $all = array_merge($credit_analyst_basic, $permi);
                    $user->push('permissions', $all);
                }
                
            }
           
            // Mail::to($request->email)->send(new WelcomeMail($user));
    
            $log = new LogController;
            $log->createLog('User '.$user->name.' with role : '.$user->role.' created.', 'User');
    
            return response([
                // 'data' => 'user created',
                // 'user_id' => $user->id
                'user' => new SuperUserResource($user)
                ]);
        }
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return new UserResource($user);
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
    public function update(UserUpdateRequest $request, User $user)
    {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->role = $request->role;
            isset($request->parent_id) ? $user->parent_id = $request->parent_id : $user->parent_id = auth('api')->user()->id;
            // $user->parent_id = $request->parent_id;
            $user->phone = $request->phone;
            $user->password = bcrypt($request->password);

            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                $avatar_name = uniqid('avatar_') . '.' . $avatar->getClientOriginalExtension();
                $path = public_path('/uploads/avatars');
                $avatar->move($path, $avatar_name);
                
                $user->avatar = $avatar_name;
            }

            $user->save();

            $merchant_basic = [
                'create-application',
                'save-application',
                // 'edit-application',
                // 'view-application',
                'submit-application',
                'create-new-contact',
                'assign-contact-to-current-application',
                'convert-contact-to-new-application',
                // 'upload-and-replace-documents'
            ];

            $broker_basic = [
                // 'create-application',
                // 'save-application',
                // 'edit-application',
                'view-application',
                // 'submit-application',
                // 'create-new-contact',
                // 'assign-contact-to-current-application',
                // 'convert-contact-to-new-application',
                // 'upload-and-replace-documents',
                'view-all-merchant-information',
                'edit-merchant-information',
                'create-new-merchant-account',
                'change-terms'
            ];

            $analyst_basic = [
                // 'create-application',
                'save-application',
                'edit-application',
                'view-application',
                'submit-application',
                'create-new-contact',
                'assign-contact-to-current-application',
                'convert-contact-to-new-application',
                'upload-and-replace-documents',
                // 'view-all-merchant-information',
                // 'edit-merchant-information',
                // 'create-new-merchant-account',
                // 'change-terms',
                'access-to-underwriting',
                'access-to-servicing',
                'access-to-collections',
                'access-archives'
            ];

            $credit_analyst_basic = [
                // 'create-application',
                'save-application',
                'edit-application',
                'view-application',
                'submit-application',
                'create-new-contact',
                'assign-contact-to-current-application',
                'convert-contact-to-new-application',
                'upload-and-replace-documents',
                // 'view-all-merchant-information',
                // 'edit-merchant-information',
                // 'create-new-merchant-account',
                // 'change-terms',
                'access-to-underwriting',
                'access-archives'
            ];

            if(isset($request->permissions)) {

                $permi = $request->permissions;
                $user->permissions_edit = $request->permissions;
                $user->permissions = [];
                $user->save();

                if($user->role == 'merchant') {

                    // $all = array_merge($merchant_basic, $request->permissions);
                    $all = array_merge($merchant_basic, $permi);
                    $user->push('permissions', $all);

                }

                if($user->role == 'broker') {

                    // $all = array_merge($broker_basic, $request->permissions);
                    $all = array_merge($broker_basic, $permi);
                    $user->push('permissions', $all);
                }

                if($user->role == 'analyst') {
                    
                    // $all = array_merge($analyst_basic, $request->permissions);
                    $all = array_merge($analyst_basic, $permi);
                    $user->push('permissions', $all);
                }

                if($user->role == 'credit_analyst') {
                    
                    // $all = array_merge($credit_analyst_basic, $request->permissions);
                    $all = array_merge($credit_analyst_basic, $permi);
                    $user->push('permissions', $all);
                }
                
            }
            
            $log = new LogController;
            $log->createLog('User '.$user->name.' with role : '.$user->role.' updated.', 'User');

            return response(['data' => 'user updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $user = User::findOrFail($id);

        // $user->delete();
        

        if (is_array($id)) {
        User::destroy($id);
            }
        else {
        User::findOrFail($id)->delete();
        }

        return response(['message' => 'user deleted']);
    }

    public function destroy_many(Request $request)
    {
        
        $id = $request->id;
        $terms = explode(',', $id);
        
        User::whereIn('_id', $terms)->delete();

        return response(['message' => 'user deleted']);
    }
}
