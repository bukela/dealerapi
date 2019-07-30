<?php

namespace App\Http\Resources;

use App\Application;
use Illuminate\Http\Resources\Json\JsonResource;

class AppFileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'app_name' => Application::findOrFail($this->application_id)->name,
            'file_type_code' => $this->file_type_code,
            'pretty_file_type_code' => $this->format_name($this->file_type_code),
            'filename' => $this->filename,
            'type' => pathinfo($this->filename, PATHINFO_EXTENSION),
            'name' => substr($this->filename, strpos($this->filename, '-') + 1),
            'file' => asset('uploads/documents/'.$this->filename),
            'id' => $this->id,
            'application_id' => $this->application_id
        ];
    }

    public function format_name($name) {

        if($name == 'id') {
            return strtoupper($name);
        }
        
        return ucwords(str_replace('_',' ',$name));

    }
}
