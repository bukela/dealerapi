<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use App\Application;

class PdfController extends Controller
{
    public function download($id) {

        $rel = ['general','loan_detail', 'employment','previous_employment','financial', 'coapplicant', 'previous_address', 'home_own','about_equipment'];
        $sample_app = Application::with($rel)->find($id);
        $sample_app = $sample_app->toArray();
        $keys = array_keys($sample_app);
        // dd($sample_app);
        $pdf = PDF::loadView('pdf', compact('sample_app','keys'));

        return $pdf->download('sample_app_'.$sample_app['_id'].'.pdf');

    }
}
