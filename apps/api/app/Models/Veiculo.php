<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Veiculo extends Model
{
    use HasFactory;

    // TABELA EM PT-BR
    protected $table = 'veiculos';

    protected $fillable = [
        'user_id',
        'marca','modelo','ano','placa','chassi',
        'km','valor_venda','cambio','combustivel','cor',
    ];

    protected $casts = [
        'ano' => 'integer',
        'km' => 'integer',
        'valor_venda' => 'decimal:2',
    ];

    // Relações em PT-BR (FK veiculo_id)
    public function imagens() { return $this->hasMany(ImagemVeiculo::class, 'veiculo_id'); }
    public function capa()    { return $this->hasOne(ImagemVeiculo::class, 'veiculo_id')->where('is_cover', true); }
    public function usuario() { return $this->belongsTo(User::class, 'user_id'); }
}
