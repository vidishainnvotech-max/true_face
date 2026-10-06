<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'public_id' => $this->public_id,
            'tenant_id' => $this->tenant_id,
            'code' => $this->code,
            'name' => $this->name,
            'legal_name' => $this->legal_name,
            'trade_name' => $this->trade_name,
            'slug' => $this->slug,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
            'timezone' => $this->timezone,
            'default_currency' => $this->default_currency,
            'country_code' => $this->country_code,
            'fiscal_year_start_month' => $this->fiscal_year_start_month,
            'status' => $this->status,
            'settings' => $this->settings,
            'effective_from' => $this->effective_from,
            'effective_to' => $this->effective_to,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}