<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VeiculoAtualizarRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('placa') && $this->input('placa') !== null) {
            $normalizada = strtoupper(str_replace(['-', ' '], '', $this->input('placa')));
            $this->merge(['placa' => $normalizada]);
        }
    }

    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $veiculo = $this->route('veiculo'); // <- binding
        $id = $veiculo?->id;

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

    /**
     * Scribe: descreve campos (todos opcionais aqui, pois é update parcial).
     */
    public function bodyParameters(): array
    {
        return [
            'marca' => [
                'description' => 'Marca do veículo.',
                'example' => 'Ford',
                'required' => false,
            ],
            'modelo' => [
                'description' => 'Modelo do veículo.',
                'example' => 'Fiesta',
                'required' => false,
            ],
            'ano' => [
                'description' => 'Ano de fabricação/modelo.',
                'example' => 2024,
                'required' => false,
            ],
            'placa' => [
                'description' => 'Placa padrão Mercosul (normalizada para ABC1D23).',
                'example' => 'ABC1D23',
                'required' => false,
            ],
            'chassi' => [
                'description' => 'Número do chassi (17 caracteres).',
                'example' => '9BWZZZ377VT004251',
                'required' => false,
            ],
            'km' => [
                'description' => 'Quilometragem atual.',
                'example' => 13000,
                'required' => false,
            ],
            'valor_venda' => [
                'description' => 'Preço de venda.',
                'example' => 78990.00,
                'required' => false,
            ],
            'cambio' => [
                'description' => 'Tipo de câmbio.',
                'example' => 'automatico',
                'required' => false,
            ],
            'combustivel' => [
                'description' => 'Tipo de combustível.',
                'example' => 'flex',
                'required' => false,
            ],
            'cor' => [
                'description' => 'Cor (opcional).',
                'example' => 'branco',
                'required' => false,
            ],
        ];
    }
}
