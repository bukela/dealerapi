<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationGeneralResource extends JsonResource
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
            'title' => $this->title,
            'amount' => $this->amount,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'sin' => $this->sin,
            'martial_status' => $this->martial_status,
            'dependents' => $this->dependents,
            'date_of_birth' => $this->date_of_birth,
            'home_phone' => $this->home_phone,
            'mobile_phone' => $this->mobile_phone,
            'email_address' => $this->email_address,
            'prefered_language' => $this->prefered_language,
            'type_of_government_id' => $this->type_of_government_id,
            'government_id_provided' => $this->government_id_provided,
            'filled' => $this->filled
        ];
    }
}
