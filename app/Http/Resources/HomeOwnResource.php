<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomeOwnResource extends JsonResource
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
            'residential_status' => $this->residential_status,
            'title_of_property' => $this->title_of_property,
            'monthly_payment' => $this->monthly_payment,
            'payment_made_to' => $this->payment_made_to,
            'outstanding_mortgage_balance' => $this->outstanding_mortgage_balance,
            'filled' => $this->filled
        ];
    }
}
