<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FinancialResource extends JsonResource
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
            'credit_card_number' => $this->credit_card_number,
            'credit_card_issuer' => $this->credit_card_issuer,
            'declared_bankruptcy' => $this->declared_bankruptcy,
            'date_of_discharge' => $this->date_of_discharge,
            'filled' => $this->filled
        ];
    }
}
