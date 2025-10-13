<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VeiculoAtualizarRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'marca'        => ['sometimes','required','string','max:60'],
            'modelo'       => ['sometimes','required','string','max:80'],
            'ano'          => ['sometimes','required','integer','between:1900,'.(date('Y')+1)],
            'placa'        => ['sometimes','required','regex:/^[A-Z]{3}\d[A-Z]\d{2}$/', "unique:veiculos,placa,{$id}"],
            'chassi'       => ['sometimes','required','string','size:17', "unique:veiculos,chassi,{$id}"],
            'km'           => ['sometimes','required','integer','min:0'],
            'valor_venda'  => ['sometimes','required','numeric','min:0'],
            'cambio'       => ['sometimes','required','in:manual,automatico,cvt'],
            'combustivel'  => ['sometimes','required','in:gasolina,etanol,flex,diesel,eletrico,hibrido'],
            'cor'          => ['nullable','string','max:30'],
        ];
    }
}