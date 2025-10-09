<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo,HasMany,HasOne};

class Veiculo extends Model
{
    protected $table = 'veiculos';

    protected $fillable = [
        'usuario_id','marca','modelo','ano','placa','chassi','km',
        'valor_venda','cambio','combustivel','cor'
    ];

    protected $casts = [
        'ano' => 'integer', 'km' => 'integer', 'valor_venda' => 'decimal:2',
    ];

    public function imagens(): HasMany { return $this->hasMany(ImagemVeiculo::class, 'veiculo_id'); }
    public function capa(): HasOne { return $this->hasOne(ImagemVeiculo::class, 'veiculo_id')->where('is_cover', true); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class, 'usuario_id'); }
}
