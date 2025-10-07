<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VehicleUpdateRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'marca'       => ['sometimes','required','string','max:60'],
            'modelo'      => ['sometimes','required','string','max:80'],
            'ano'         => ['sometimes','required','integer','between:1900,'.(date('Y')+1)],
            'placa'       => ['sometimes','required','string','max:10', Rule::unique('vehicles','placa')->ignore($id)],
            'chassi'      => ['sometimes','required','string','size:17', Rule::unique('vehicles','chassi')->ignore($id)],
            'km'          => ['sometimes','required','integer','min:0'],
            'valor_venda' => ['sometimes','required','numeric','min:0'],
            'cambio'      => ['sometimes','required','in:manual,automatico,cvt'],
            'combustivel' => ['sometimes','required','in:gasolina,etanol,flex,diesel,eletrico,hibrido'],
            'cor'         => ['nullable','string','max:30'],
        ];
    }
}
