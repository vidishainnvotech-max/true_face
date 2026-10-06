<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'public_id' => $this->public_id ?? null,
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'status' => $this->status,

            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->map(function ($role) {
                    return [
                        'public_id' => $role->public_id,
                        'code' => $role->code,
                        'name' => $role->name,
                        'scope_level' => $role->scope_level,
                        'status' => $role->status,
                    ];
                });
            }),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}