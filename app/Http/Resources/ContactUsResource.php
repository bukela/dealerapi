<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactUsResource extends JsonResource
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
            'application_id' => $this->application_id,
            'user_id' => $this->user_id,
            'sender_name' => !empty(User::find($this->user_id)) ? User::find($this->user_id)->name : 'unknown',
            'emergency_type' => $this->emergency_type,
            'message' => $this->message,
            'phone_number' => $this->phone_number,
            'email' => $this->email
        ];
    }
}
