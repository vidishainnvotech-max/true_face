<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\User;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'public_id',
        'code',
        'name',
        'slug',
        'status',
        'isolation_mode',
        'default_timezone',
        'default_locale',
        'country_code',
        'data_residency_region',
        'settings',
        'activated_at',
        'suspended_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'activated_at' => 'datetime',
        'suspended_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {

            if (empty($tenant->public_id)) {
                $tenant->public_id = (string) Str::ulid();
            }

            if (empty($tenant->slug)) {
                $tenant->slug = Str::slug($tenant->name);
            }

        });
    }

    public function getRouteKeyName()
    {
        return 'public_id';
    }


    public function users()
{
    return $this->hasMany(User::class);
}

}