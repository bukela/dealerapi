<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AboutEquipmentResource extends JsonResource
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
            'application_id' => $this->application_id,
            'program' => $this->program,
            'class' => $this->class,
            'type' => $this->type,
            'description' => $this->description,
            'vehicle_year' => $this->vehicle_year,
            'vehicle_odometer' => $this->vehicle_odometer,
            'serial_number' => $this->serial_number,
            'equipment_cost' => $this->equipment_cost,
            'merchant' => $this->merchant,
            'merchant_rep' => $this->merchant_rep,
            'data_entry' => $this->data_entry
        ];
    }
}
