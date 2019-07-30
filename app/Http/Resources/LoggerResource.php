<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoggerResource extends JsonResource
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
            'id' => $this->id,
            'username' => $this->username,
            'model' => $this->model,
            'model_id' => $this->model_id,
            'description' => $this->description,
            'date' => date('F d. Y', strtotime($this->created_at))
        ];
    }
}
