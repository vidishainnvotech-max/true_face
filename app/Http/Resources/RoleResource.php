<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'public_id' => $this->public_id,

            'tenant_id' => $this->tenant_id,

            'code' => $this->code,

            'name' => $this->name,

            'description' => $this->description,

            'scope_level' => $this->scope_level,

            'is_system' => $this->is_system,

            'is_assignable' => $this->is_assignable,

            'priority' => $this->priority,

            'status' => $this->status,

            'created_by_user_id' => $this->created_by_user_id,

            'updated_by_user_id' => $this->updated_by_user_id,

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,

            'deleted_at' => $this->deleted_at,
        ];
    }
}