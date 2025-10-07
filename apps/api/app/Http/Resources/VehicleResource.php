<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,'marca'=>$this->marca,'modelo'=>$this->modelo,'ano'=>$this->ano,
            'placa'=>$this->placa,'chassi'=>$this->chassi,'km'=>$this->km,
            'valor_venda'=>(float)$this->valor_venda,'cambio'=>$this->cambio,
            'combustivel'=>$this->combustivel,'cor'=>$this->cor,
            'cover_url'=>$this->cover? asset('storage/'.$this->cover->path):null,
            'images'=>$this->whenLoaded('images', fn()=> $this->images->map(fn($i)=>[
                'id'=>$i->id,'url'=>asset('storage/'.$i->path),'is_cover'=>$i->is_cover,'order'=>$i->order,
            ])),
            'created_at'=>$this->created_at,'updated_at'=>$this->updated_at,
        ];
    }
    
}
