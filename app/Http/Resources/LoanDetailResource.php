<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LoanDetailResource extends JsonResource
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
            'down_payment' => $this->down_payment,
            'trade' => $this->trade,
            'payment_frequency' => $this->payment_frequency,
            'payment_term' => $this->payment_term,
            'amort_term' => $this->amort_term,
            'defferal_periods' => $this->defferal_periods,
            'amount_owing_at_the_end_of_loan_term' => $this->amount_owing_at_the_end_of_loan_term,
            'rate' => $this->rate,
            'contract_start_date' => $this->contract_start_date,
            'first_payment_date' => $this->first_payment_date,
            'payment' => $this->payment,
            'filled' => $this->filled
        ];
    }
}
