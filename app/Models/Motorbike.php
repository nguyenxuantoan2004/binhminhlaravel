<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// xe
class Motorbike extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ["name", "slug", "quantity", "manufacture_year", "color", "vehicle_condition", "status", "rental_price", "motorbike_image_id", "category_motorbike_id", "supplier_id"];


    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'pending' => 'Chờ duyệt',
            'draft' => 'Nháp',
            'public' => 'Công khai',
            'private' => 'Không công khai',
            'maintenance' => 'Đang bảo trì',
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
            'maintenance' => 'badge text-bg-warning',
        ];

        return $statuses[$this->attributes['status']] ?? 'Không xác định';
    }

    public function MotorbikeImage()
    {
        return $this->hasMany(MotorbikeImage::class, 'motorbike_id');
    }

    public function pinnedImage()
    {
        return $this->hasOne(MotorbikeImage::class, "motorbike_id")->where('pin', 1);
    }

    public function categoryMotorbike()
    {
        return $this->belongsTo(CategoryMotorbike::class, "category_motorbike_id");
    }

    public function categoryMotorbikeBySlug()
    {
        return $this->belongsTo(CategoryMotorbike::class, 'category_motorbike_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
