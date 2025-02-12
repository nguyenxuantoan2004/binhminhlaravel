<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// hóa đơn
class Invoice extends Model
{
    //
    use SoftDeletes;
    protected $fillable = ["start_date", "expected_return_date", "actual_return_date", "vat_fee", "phone", "email", "total_rental_duration", "additional_fees", "total_cost", "total_amount", "motorbike_receipt_method", "motorbike_pickup_location", "status", "motorbike_id", "customer_id", "employee_id", "branch_id"];
    protected $casts = [
        'expected_return_date' => 'datetime',
        'actual_return_date' => 'datetime',
        'start_date' => 'datetime',
    ];
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'pending' => 'Chờ xác nhận',
            'confirmed' => 'Đã xác nhận',
            'delivering' => 'Đang giao xe',
            'waiting_for_pickup' => 'Chờ nhận xe',
            'picked_up' => 'Đã nhận xe',
            'completed' => 'Hoàn thành',
            'cancelled' => 'Đã hủy',
        ];

        return $statuses[$this->attributes['status']] ?? 'Không xác định';
    }

    public function getStatusClassAttribute(): string
    {
        $statuses = [
            'pending' => 'badge text-bg-info',
            'confirmed' => 'badge text-bg-primary',
            'delivering' => 'badge text-bg-warning',
            'waiting_for_pickup' => 'badge text-bg-secondary',
            'picked_up' => 'badge text-bg-success',
            'completed' => 'badge text-bg-success',
            'cancelled' => 'badge text-bg-danger',
        ];

        return $statuses[$this->attributes['status']] ?? 'badge text-bg-light';
    }
    public function motorbike()
    {
        return $this->belongsTo(Motorbike::class);
    }

    function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
