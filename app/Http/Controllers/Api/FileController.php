<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Application;
use App\File as AppFile;
use Illuminate\Http\Request;
use App\Http\Requests\FileRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use Illuminate\Support\Facades\File;
use App\Http\Resources\AppFileResource;
use App\Http\Resources\AppFileNewResource;
use App\Http\Resources\ApplicationFilesResource;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $terms = explode(',', $search);

        if(isset($search)) {

            return FileResource::collection(AppFile::whereIn('file_type_code', $terms)->get());

        } else {

            return FileResource::collection(AppFile::all());

        }
        
    }

    public function app_files(Request $request, $id)
    {
        $search = $request->get('search');
        // dd($search);
        $terms = explode(',', $search);
        // dd($search);

        if(isset($search)) {

            return FileResource::collection(AppFile::where('application_id', $id)->whereIn('file_type_code', $terms)->get());

        } else {

            return FileResource::collection(AppFile::where('application_id', $id)->get());

        }
        
    }

    public function analyst_files(Request $request)
    {
        $search = $request->get('search');
        // dd($search);
        $terms = explode(',', $search);
        // dd($search);

        if(isset($search)) {

            // return FileResource::collection(AppFile::whereIn('file_type_code', $terms)->get());
            return AppFileResource::collection(AppFile::whereIn('file_type_code', $terms)->get())->groupBy('application_id');


        } else {

            // return FileResource::collection(AppFile::all());
            return AppFileResource::collection(AppFile::all())->groupBy('application_id');


        }
        
    }


    public function superuser_files(Request $request)
    {
        $search = $request->get('search');
        // dd($search);
        $terms = explode(',', $search);
        // dd($search);

        if(isset($search)) {

            // return FileResource::collection(AppFile::whereIn('file_type_code', $terms)->get());
            return AppFileNewResource::collection(AppFile::whereIn('file_type_code', $terms)->get())->groupBy('application_id');


        } else {

            // return FileResource::collection(AppFile::all());
            return AppFileResource::collection(AppFile::all())->groupBy('application_id');


        }
        
    }

    public function user_files(Request $request) {

        $search = $request->get('search');
        $terms = explode(',', $search);

        $user = auth('api')->user()->id;
        $user_apps = Application::where('user_id', $user)->with('files')->get();
        $ids = $user_apps->pluck('id');
        
        $app_files = AppFile::whereIn('application_id', $ids)->get(); 

        if(isset($search)) {

            // return FileResource::collection(AppFile::whereIn('file_type_code', $terms)->get());
            return AppFileResource::collection($app_files->whereIn('file_type_code', $terms))->groupBy('application_id');


        } else {

            // return FileResource::collection(AppFile::all());
            return AppFileResource::collection($app_files)->groupBy('application_id');


        }

    }

    public function broker_files(Request $request) {

        $search = $request->get('search');
        $terms = explode(',', $search);

        $user = auth('api')->user()->id;

        $merchants_id = User::where('parent_id', $user)->pluck('_id')->toArray();
        array_push($merchants_id,$user);

        $user_apps = Application::whereIn('user_id', $merchants_id)->get();

        $ids = $user_apps->pluck('id');
        
        $app_files = AppFile::whereIn('application_id', $ids)->get(); 

        if(isset($search)) {

            // return FileResource::collection(AppFile::whereIn('file_type_code', $terms)->get());
            return AppFileResource::collection($app_files->whereIn('file_type_code', $terms))->groupBy('application_id');


        } else {

            // return FileResource::collection(AppFile::all());
            return AppFileResource::collection($app_files)->groupBy('application_id');


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
    // public function store(FileRequest $request)
    // {

    //     if ($request->hasFile('files')) {

    //         $files = $request->file('files');

    //         foreach($files as $file) {
    //         $filename = uniqid('doc_') . '-' . $file->getClientOriginalName();
    //         $filename = str_replace(' ', '_' ,$filename);
    //         $path = public_path('/uploads/documents');
    //         $uploaded = $file->move($path, $filename);


    //         $app_document = new AppFile;
    //         $app_document->application_id = $request->application_id;
    //         $app_document->file_type_code = $request->file_type_code;
    //         $app_document->filename = $uploaded->getFilename();
    //         $app_document->save();

    //         $log = new LogController;
    //         if(isset($app_document->application->name)) {
    //             $log->createLog('File uploaded for '.$app_document->application->name, 'File');
    //         } else {
    //             $log->createLog('File uploaded for "name unknown"', 'File');
    //         }
    //         }
    //     }


    //         //single file upload if needed

    //         // $file = $request->file('file');

    //         // $filename = uniqid('doc_') . '-' . $file->getClientOriginalName();
    //         // $filename = str_replace(' ', '_' ,$filename);
    //         // $path = public_path('/uploads/documents');
    //         // $uploaded = $file->move($path, $filename);
            
    //         // $app_document = new AppFile;
    //         // $app_document->application_id = $request->application_id;
    //         // $app_document->file_type_code = $request->file_type_code;
    //         // $app_document->filename = $uploaded->getFilename();
    //         // $app_document->save();


    //     return response(['message' => 'file created']);
    // }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $file = AppFile::findOrFail($id);
        return new AppFileResource($file);
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
    public function update(FileRequest $request, $id)
    {

        // $request->validate([
        //     'files' => 'mimes:pdf,docx,doc,png,jpg,jpeg,gif,odt|max:6000|required'
        // ]);

        $app = Application::findOrFail($id);
        $file_types = $app->files->pluck('file_type_code')->toArray();
        $app_file = AppFile::where('application_id', $id);

        $file = $request->file('files');
            // dd($file);
            $filename = uniqid('doc_') . '-' . $file->getClientOriginalName();
            $filename = str_replace(' ', '_' ,$filename);
            $path = public_path('/uploads/documents');
            $uploaded = $file->move($path, $filename);

        

        if(in_array($request->file_type_code, $file_types)) {

            $for_delete = $app_file->where('file_type_code',$request->file_type_code)->get();
            File::delete(public_path('uploads/documents/'.$for_delete->first()->filename));

            $approved = 0;
            $request->approved == 'on' ? $approved = 1 : $approved = 0;

            $app_file->where('file_type_code',$request->file_type_code)->update([
                'filename' => $uploaded->getFilename(),
                'approved' => $approved
            ]);

            $user = auth('api')->user()->name;
          
            $log = new FileLogController;
            $log->createLog( 
                date('F j,Y').'-'.ucwords(str_replace('_', ' ', $app_file->first()->file_type_code)).' file uploaded by-'.$user.', for-'.$app->name.' application', 'File', $app_file->first()->_id);

            $updated = $app_file->where('file_type_code',$request->file_type_code);
            return response([
                    'file_type_code' =>$updated->first()->file_type_code,
                    'filename' => $updated->first()->filename,
                    'approved' => $updated->first()->approved,
                    'name' => substr($updated->first()->filename, strpos($updated->first()->filename, '-') + 1),
                    'file' => asset('uploads/documents/'.$updated->first()->filename),
                    '_id' => $updated->first()->id
            ]);

        } else {

            $app_document = new AppFile;
            $app_document->application_id = $id;
            $app_document->file_type_code = $request->file_type_code;
            $request->approved == 'on' ? $app_document->approved = 1 : $app_document->approved = 0;
            $app_document->filename = $uploaded->getFilename();

            // dd($app_document);
            $app_document->save();

            $user = auth('api')->user()->name;
            $log = new FileLogController;
            
            $log->createLog( 
                date('F j,Y').'-'.ucwords(str_replace('_', ' ', $app_document->file_type_code)).' file uploaded by-'.$user.', for-'.$app->name.' application', 'File',$app_document->id
            );
            
            return response([
                // 'data' => [
                    'file_type_code' =>$app_document->file_type_code,
                    'filename' => $app_document->filename,
                    'approved' => $app_document->approved,
                    'name' => substr($app_document->filename, strpos($app_document->filename, '-') + 1),
                    'file' => asset('uploads/documents/'.$app_document->filename),
                    '_id' => $app_document->id
                    
                    
                // ]
            ]);
        }

            // $file = $request->file('files');
            // // dd($file);
            // $filename = uniqid('doc_') . '-' . $file->getClientOriginalName();
            // $filename = str_replace(' ', '_' ,$filename);
            // $path = public_path('/uploads/documents');
            // $uploaded = $file->move($path, $filename);
            
            // $app_document = AppFile::findOrFail($id);
            // // $app_document->application_id = $request->application_id;
            // $app_document->file_type_code = $request->file_type_code;
            // $app_document->filename = $uploaded->getFilename();
            // $app_document->save();
            
            // return response(['file updated']);
            
        }

        
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $app_document = AppFile::findOrFail($id);
        File::delete(public_path('uploads/documents/'.$app_document->filename));
        $app_document->delete();

        $log = new LogController;
        $log->createLog( ucwords(str_replace('_', ' ', $app_document->file_type_code)).' uploaded for application id: '.$app_document->application_id, 'File');

        return response(['message' => 'file deleted']);
    }

    public function download($id) {

        $file = AppFile::findOrFail($id);

        if($file) {

            return response()->download(public_path('/uploads/documents/'.$file->filename));

        } else {

            return response(['no file provided']);

        }
    }
}
