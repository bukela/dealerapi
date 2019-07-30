<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmploymentResource extends JsonResource
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
            'employment_status' => $this->employment_status,
            'company_name' => $this->company_name,
            'company_address' => $this->company_address,
            'company_city' => $this->company_city,
            'company_province' => $this->company_province,
            'company_postal_code' => $this->company_postal_code,
            'company_telephone' => $this->company_telephone,
            'company_type_of_business' => $this->company_type_of_business,
            'company_salary' => $this->company_salary,
            'company_salary_type' => $this->company_salary_type,
            'company_months_of_employment' => $this->company_months_of_employment,
            'company_years_of_employment' => $this->company_years_of_employment,
            'company_position' => $this->company_position,
            'filled' => $this->filled
        ];
    }
}
