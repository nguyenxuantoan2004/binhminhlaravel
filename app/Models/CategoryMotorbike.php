<?php

namespace App\Models;

use App\Enums\StatusGlobal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// loại xe
class CategoryMotorbike extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ["name", "slug","status"];

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

    function motorbike(){
        return $this->hasMany(Motorbike::class, "category_motorbike_id");
    }

    public function motorbikeBySlug()
    {
        return $this->hasMany(CategoryMotorbike::class, 'category_motorbike_id');
    }
}
