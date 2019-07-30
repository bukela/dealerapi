<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
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
            // 'application_name' => !empty(\App\Application::find($this->application_id)->name) ? \App\Application::find($this->application_id)->name : 'no name',
            'file_type_code' => $this->file_type_code,
            'filename' => $this->filename,
            'approved' => $this->approved,
            'name' => substr($this->filename, strpos($this->filename, '-') + 1),
            'file' => asset('uploads/documents/'.$this->filename),
            'id' => $this->id,
            'application_id' => $this->application_id
        ];
    }
}
