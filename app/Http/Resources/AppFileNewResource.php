<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppFileNewResource extends JsonResource
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
            'file_id' => $this->id,
            'file_type_code' => $this->file_type_code,
            'filename' => $this->filename,
            'type' => pathinfo($this->filename, PATHINFO_EXTENSION),
            'original_name' => substr($this->filename, strpos($this->filename, '-') + 1),
            'file_url' => asset('uploads/documents/'.$this->filename)
        ];
    }
}
