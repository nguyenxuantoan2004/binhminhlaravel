<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// nhân viên
class Employee extends Authenticatable
{
    //
    use SoftDeletes, Notifiable;
    protected $fillable = ["name", "email", "phone_number", "id_card_number", "salary","address", "password", "status","branch_id","position_id"];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
//Laravel Accessor tạo thuộc tính ảo
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

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    public function post()
    {
        return $this->hasMany(Post::class, 'employee_id');
    }

    public function page()
    {
        return $this->hasMany(Page::class, 'employee_id');
    }
}
