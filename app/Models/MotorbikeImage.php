<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorbikeImage extends Model
{
    //
    protected $fillable = ["file_name", "motorbike_id", "pin"];

    public function Motorbike(){
        return $this->belongsTo(Motorbike::class, 'motorbike_id');
    }
}
