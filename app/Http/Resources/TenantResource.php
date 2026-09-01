<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
{
    return [
        'public_id' => $this->public_id,
        'code' => $this->code,
        'name' => $this->name,
        'slug' => $this->slug,
        'status' => $this->status,
        'isolation_mode' => $this->isolation_mode,
        'default_timezone' => $this->default_timezone,
        'default_locale' => $this->default_locale,
        'country_code' => $this->country_code,
        'data_residency_region' => $this->data_residency_region,
        'settings' => $this->settings,
        'activated_at' => $this->activated_at,
        'suspended_at' => $this->suspended_at,
        'created_at' => $this->created_at,
        'updated_at' => $this->updated_at,
    ];
}
}
