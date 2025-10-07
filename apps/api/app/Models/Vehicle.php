<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'user_id','marca','modelo','ano','placa','chassi','km','valor_venda',
        'cambio','combustivel','cor'
    ];

    protected $casts = [
        'ano'=>'integer','km'=>'integer','valor_venda'=>'decimal:2',
    ];

    public function images() { return $this->hasMany(VehicleImage::class); }
    public function cover()  { return $this->hasOne(VehicleImage::class)->where('is_cover', true); }
    public function user()   { return $this->belongsTo(User::class); }
}
