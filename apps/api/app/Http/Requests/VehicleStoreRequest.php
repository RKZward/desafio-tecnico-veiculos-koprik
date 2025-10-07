<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VehicleStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'marca'       => ['required','string','max:60'],
            'modelo'      => ['required','string','max:80'],
            'ano'         => ['required','integer','between:1900,'.(date('Y')+1)],
            'placa'       => ['required','string','max:10','unique:vehicles,placa'],
            'chassi'      => ['required','string','size:17','unique:vehicles,chassi'],
            'km'          => ['required','integer','min:0'],
            'valor_venda' => ['required','numeric','min:0'],
            'cambio'      => ['required','in:manual,automatico,cvt'],
            'combustivel' => ['required','in:gasolina,etanol,flex,diesel,eletrico,hibrido'],
            'cor'         => ['nullable','string','max:30'],
        ];
    }
    
}
