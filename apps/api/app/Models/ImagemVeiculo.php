<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagemVeiculo extends Model
{
    protected $table = 'imagens_veiculo';

    protected $fillable = ['veiculo_id','path','is_cover','order'];

    protected $casts = ['is_cover'=>'boolean','order'=>'integer'];

    public function veiculo(): BelongsTo { return $this->belongsTo(Veiculo::class, 'veiculo_id'); }
}