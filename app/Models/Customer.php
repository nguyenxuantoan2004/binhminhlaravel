<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
// khách hàng
class Customer extends Authenticatable
{
    //
    use SoftDeletes, Notifiable;
    protected $fillable = ["name", "email", "id_card_number", "driving_license_number", "address", "phone_number", "password", "status"];
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'active' => 'Hoạt động',
            'temporary_lock' => 'Khóa tạm thời',
            'permanently_locked' => 'Khóa vĩnh viễn',
        ];

        return $statuses[$this->attributes['status']] ?? 'Không xác định';
    }

    public function getClassAttribute(): string
    {
        $statuses = [
            'active' => 'badge text-bg-success',
            'temporary_lock' => 'badge text-bg-warning',
            'permanently_locked' => 'badge text-bg-danger',
        ];

        return $statuses[$this->attributes['status']] ?? 'Không xác định';
    }

    function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
