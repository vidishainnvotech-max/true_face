<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'public_id',
        'code',
        'name',
        'legal_name',
        'trade_name',
        'slug',
        'email',
        'phone',
        'website',
        'timezone',
        'default_currency',
        'country_code',
        'fiscal_year_start_month',
        'status',
        'settings',
        'effective_from',
        'effective_to',
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

        static::creating(function ($company) {

            if (empty($company->public_id)) {
                $company->public_id = (string) Str::ulid();
            }

            if (empty($company->slug)) {
                $company->slug = Str::slug($company->name);
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
}