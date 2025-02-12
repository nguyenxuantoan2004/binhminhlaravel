<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// chức vụ
class Position extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ["name","status"];

    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'pending' => 'Chờ duyệt',
            'draft' => 'Nháp',
            'public' => 'Công khai',
            'private' => 'Không công khai',
        ];
    
        return $statuses[$this->attributes['status']] ?? 'Không xác định';
    }

    public function getClassAttribute(): string
    {
        $statuses = [
            'pending' => 'badge text-bg-info',
            'draft' => 'badge text-bg-secondary',
            'public' => 'badge text-bg-success',
            'private' => 'badge text-bg-danger',
        ];
    
        return $statuses[$this->attributes['status']] ?? 'Không xác định';
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
