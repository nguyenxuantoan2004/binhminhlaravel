<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//bài viết
class Post extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ["title", "slug", "short_description", "content", "thumbnail", "status", "employee_id"];
    

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

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
