<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LegalEntity extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'company_id',
        'public_id',
        'code',
        'legal_name',
        'registration_number',
        'tax_identifier',
        'email',
        'phone',
        'address_line_1',
        'address_line_2',
        'city',
        'state_code',
        'country_code',
        'postal_code',
        'status',
        'effective_from',
        'effective_to',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'effective_from' => 'date',
        'effective_to' => 'date',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($legalEntity) {

            if (empty($legalEntity->public_id)) {
                $legalEntity->public_id = (string) Str::ulid();
            }

        });
    }

    public function getRouteKeyName()
    {
        return 'public_id';
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}