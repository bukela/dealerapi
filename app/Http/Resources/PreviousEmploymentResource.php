<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PreviousEmploymentResource extends JsonResource
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
            'previous_company_name' => $this->previous_company_name,
            'previous_company_address' => $this->previous_company_address,
            'previous_company_city' => $this->previous_company_city,
            'previous_company_province' => $this->previous_company_province,
            'previous_company_postal_code' => $this->previous_company_postal_code,
            'previous_company_telephone' => $this->previous_company_telephone,
            'previous_company_years_of_employment' => $this->previous_company_years_of_employment,
            'filled' => $this->filled
        ];
    }
}
