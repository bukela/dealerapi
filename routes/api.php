<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationCompleteResource;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//redirect all pages to '/'
// Route::get('{any}', function() {
//     return redirect('/');
//  })->where('any', '.*');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

    Route::group(['namespace' => 'Api'], function() {

        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');
    
        Route::group(['middleware' => 'auth:api'], function(){
            Route::get('logout', 'AuthController@logout');
            Route::resource('/users', 'UserController');
            Route::get('/users-delete', 'UserController@destroy_many');
            Route::resource('/applications', 'ApplicationController');
            Route::get('/app-cleaner', 'ApplicationController@non_updated');
            Route::get('/purge', 'ApplicationController@purge');
            Route::resource('/files', 'FileController');
            Route::get('/app-files/{id}', 'FileController@app_files');
            Route::get('/merchant-app-files', 'FileController@user_files');
            Route::get('/analyst-app-files', 'FileController@analyst_files');
            Route::get('/superuser-app-files', 'FileController@superuser_files');
            Route::get('/broker-app-files', 'FileController@broker_files');
            Route::get('/file/download/{id}', 'FileController@download');
            // Route::resource('/financial-infos', 'FinancialInformationController');
            Route::resource('/employments', 'EmploymentController');
            Route::resource('/application-generals', 'ApplicationGeneralController');
            Route::resource('/loan-details', 'LoanDetailController');
            Route::resource('/pre-employments', 'PreviousEmploymentController');
            Route::resource('/co-applicants', 'CoApplicantController');
            Route::resource('/pre-addresses', 'PreviousAddressController');
            Route::resource('/cur-addresses', 'CurrentAddressController');
            Route::resource('/home-ownership', 'HomeOwnController');
            Route::resource('/about-equipment', 'EquipmentTypeController');
            Route::resource('/contact-us', 'ContactUsController');
            Route::get('/user-applications', 'DashboardController@user_applications');
            // Route::get('/user-app-started', 'DashboardController@started');
            // Route::get('/app-started', 'DashboardController@analyst_started');
            // Route::get('/app-approved', 'DashboardController@approved');
            // Route::get('/app-completed', 'DashboardController@completed');
            // Route::get('/app-submitted', 'DashboardController@submitted');
            Route::get('/analyst-applications', 'DashboardController@analyst_applications');
            Route::get('/broker-applications', 'DashboardController@broker_applications');
            Route::get('/super-user-applications', 'DashboardController@super_applications');
            Route::get('/merchant-applications', 'DashboardController@merchant_applications');
            Route::get('/alla', 'DashboardController@alla');
            Route::get('/all-merchants', 'AnalystController@merchants');
            Route::get('/all-brokers', 'AnalystController@brokers');
            Route::get('/all-analysts', 'AnalystController@analysts');
            Route::get('/application-documents/{id}', 'DocumentController@app_docs');
            Route::get('/documents', 'DocumentController@merchant_documents');
            Route::get('/super-documents', 'DocumentController@super_documents');
            Route::get('/broker-documents', 'DocumentController@broker_documents');
            Route::get('/logs', 'LogController@index');
            Route::delete('/logs', 'LogController@destroy');
            Route::get('/pdf/{id}','PdfController@download');
            Route::post('message', 'MessageController@store')->name('message-send');
            // Route::get('messages', 'MessageController@index')->name('message-index');
            Route::delete('message/{id}', 'MessageController@destroy')->name('message-delete');
            Route::get('messages/{id}', 'MessageController@show')->name('message-show');
            
            
        });
    });

    Route::get('messages', 'Api\MessageController@index')->name('message-index');
    Route::get('/chat', function() {
        return view('chat');
    });

    //password reset

    // Route::group([    
    //     'namespace' => 'Api',    
    //     'middleware' => 'auth:api',    
    //     'prefix' => 'password'
    // ], function () {    
    //     Route::post('create', 'PasswordResetController@create');
    //     Route::get('find/{token}', 'PasswordResetController@find');
    //     Route::post('reset', 'PasswordResetController@reset');
    // });

    Route::group([    
        'namespace' => 'Api',    
        // 'middleware' => 'auth:api',    
        'prefix' => 'password'
    ], function () {    
        Route::post('create', 'PasswordResetController@forgot_create');
        Route::get('reset-password/{token}', 'PasswordResetController@forgot_find');
        Route::post('reset', 'PasswordResetController@forgot_reset');
    });

    Route::get('/get-soap', 'Api\SoapController@get_soap');
    // Route::get('/soap-application/{id}', 'Api\ApplicationSoapController@app_send');
    Route::get('/soap-application/{id}', 'Api\ApplicationSoapController@app_login');
    Route::post('/soap-file-upload/{id}', 'Api\SoapFileUploadController@app_login');
    Route::post('/upload-test', 'Api\SoapFileUploadController@file_test');