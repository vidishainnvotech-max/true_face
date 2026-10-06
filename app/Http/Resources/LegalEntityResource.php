<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LegalEntityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'public_id' => $this->public_id,
            'tenant_id' => $this->tenant_id,
            'company_id' => $this->company_id,

            'code' => $this->code,
            'legal_name' => $this->legal_name,

            'registration_number' => $this->registration_number,
            'tax_identifier' => $this->tax_identifier,

            'email' => $this->email,
            'phone' => $this->phone,

            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,

            'city' => $this->city,
            'state_code' => $this->state_code,
            'country_code' => $this->country_code,
            'postal_code' => $this->postal_code,

            'status' => $this->status,

            'effective_from' => $this->effective_from,
            'effective_to' => $this->effective_to,

            'settings' => $this->settings,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}