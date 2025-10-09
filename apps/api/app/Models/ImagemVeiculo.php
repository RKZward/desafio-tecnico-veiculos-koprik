<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImagemVeiculo extends Model
{
    // TABELA EM PT-BR
    protected $table = 'imagens_veiculo';

    protected $fillable = ['veiculo_id','path','is_cover','order'];

    protected $casts = ['is_cover' => 'boolean', 'order' => 'integer'];

    public function veiculo() { return $this->belongsTo(Veiculo::class, 'vehicle_id'); }
}
