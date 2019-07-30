<?php

namespace App\Http\Resources;

use App\User;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationCompleteResource extends JsonResource
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
            'app_number' => $this->app_number,
            'user_id' => $this->user_id,
            'user_name' => !empty(User::find($this->user_id)) ? User::find($this->user_id)->name : 'unknown',
            'type' => $this->type,
            'status' => $this->status,
            'name' => $this->name,
            'created_at' => date($this->created_at),
            'generals' => new ApplicationGeneralResource($this->general),
            'home_own' => new HomeOwnResource($this->home_own),
            'co_applicant' => new CoApplicantResource($this->coapplicant),
            'current_address' => new CurrentAddressResource($this->current_address),
            'previous_employment' => new PreviousEmploymentResource($this->previous_employment),
            'employment' => new EmploymentResource($this->employment),
            // 'financial' => new FinancialResource($this->financial),
            'about_equipment' => new AboutEquipmentResource($this->about_equipment),
            'loan_detail' => new LoanDetailResource($this->loan_detail),
            'previous_address' => new PreviousAddressResource($this->previous_address),
            'previous_employment' => new PreviousEmploymentResource($this->previous_employment),
            'files' => FileResource::collection($this->files),
        ];
    }
}
