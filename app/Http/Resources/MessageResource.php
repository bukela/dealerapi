<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'sender_id' => $this->sender_id,
            'sender' => User::findOrFail($this->sender_id) !== '' ? User::findOrFail($this->sender_id)->name : 'sender unknown',
            'body' => $this->body,
            'created_at' => date('F j,Y g:i', strtotime($this->created_at))
        ];
    }
}
