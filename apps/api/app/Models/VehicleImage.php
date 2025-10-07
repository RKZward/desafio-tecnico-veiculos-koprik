<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleImage extends Model
{
    protected $fillable = ['vehicle_id','path','is_cover','order'];
    protected $casts = ['is_cover'=>'boolean','order'=>'integer'];
    public function vehicle() { return $this->belongsTo(Vehicle::class); }
}
