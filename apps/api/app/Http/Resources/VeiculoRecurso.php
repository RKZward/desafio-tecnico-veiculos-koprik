<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VeiculoRecurso extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'marca'       => $this->marca,
            'modelo'      => $this->modelo,
            'ano'         => $this->ano,
            'placa'       => $this->placa,
            'chassi'      => $this->chassi,
            'km'          => $this->km,
            'valor_venda' => (float) $this->valor_venda,
            'cambio'      => $this->cambio,
            'combustivel' => $this->combustivel,
            'cor'         => $this->cor,
            'capa_url'    => $this->capa ? asset('storage/'.$this->capa->path) : null,
            'imagens'     => $this->whenLoaded('imagens', fn() =>
                $this->imagens->map(fn($img) => [
                    'id' => $img->id,
                    'url'=> asset('storage/'.$img->path),
                    'is_cover'=>$img->is_cover,
                    'order'=>$img->order,
                ])
            ),
            'criado_em'   => $this->created_at,
            'atualizado_em'=> $this->updated_at,
        ];
    }
}
