<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CoApplicantResource extends JsonResource
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
            'business_type' => $this->business_type,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'sin' => $this->sin,
            'date_of_birth' =>$this->date_of_birth,
            'home_phone' => $this->home_phone,
            'mobile_phone' => $this->mobile_phone,
            'email_address' => $this->email_address,
            'street_name' => $this->street_name,
            'appt_po' => $this->appt_po,
            'street_number' => $this->street_number,
            'city' => $this->city,
            'province' => $this->province,
            'postal_code' => $this->postal_code,
            'rent_or_own' => $this->rent_or_own,
            'monthly_housing_payment' => $this->monthly_housing_payment,
            'relationship_to_applicant' => $this->relationship_to_applicant,
            'company_name' => $this->company_name,
            'employer_address' => $this->employer_address,
            'employer_phone' => $this->employer_phone,
            'number_of_years_employed' => $this->number_of_years_employed,
            'number_of_months_employed' => $this->number_of_months_employed,
            'gross_monthly_income' => $this->gross_monthly_income,
            'position' => $this->position,
            'filled' => $this->filled
        ];
    }
}