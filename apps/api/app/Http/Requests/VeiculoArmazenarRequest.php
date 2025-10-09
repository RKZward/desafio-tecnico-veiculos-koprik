<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VeiculoArmazenarRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'marca'        => ['required','string','max:60'],
            'modelo'       => ['required','string','max:80'],
            'ano'          => ['required','integer','between:1900,'.(date('Y')+1)],
            'placa'        => ['required','regex:/^[A-Z]{3}\d[A-Z]\d{2}$/','unique:veiculos,placa'],
            'chassi'       => ['required','string','size:17','unique:veiculos,chassi'],
            'km'           => ['required','integer','min:0'],
            'valor_venda'  => ['required','numeric','min:0'],
            'cambio'       => ['required','in:manual,automatico,cvt'],
            'combustivel'  => ['required','in:gasolina,etanol,flex,diesel,eletrico,hibrido'],
            'cor'          => ['nullable','string','max:30'],
        ];
    }
}