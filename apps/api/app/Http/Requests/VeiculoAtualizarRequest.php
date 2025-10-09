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
            'placa'        => ['sometimes','required','string','max:10', Rule::unique('veiculos','placa')->ignore($id)],
            'chassi'       => ['sometimes','required','string','size:17', Rule::unique('veiculos','chassi')->ignore($id)],
            'km'           => ['sometimes','required','integer','min:0'],
            'valor_venda'  => ['sometimes','required','numeric','min:0'],
            'cambio'       => ['sometimes','required','in:manual,automatico,cvt'],
            'combustivel'  => ['sometimes','required','in:gasolina,etanol,flex,diesel,eletrico,hibrido'],
            'cor'          => ['nullable','string','max:30'],
        ];
    }
}