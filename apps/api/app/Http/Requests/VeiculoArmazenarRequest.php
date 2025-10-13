<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VeiculoArmazenarRequest extends FormRequest
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

    /**
     * Scribe: descrição dos parâmetros do corpo da requisição.
     */
    public function bodyParameters(): array
    {
        return [
            'marca' => [
                'description' => 'Marca do veículo.',
                'example' => 'Ford',
                'required' => true,
            ],
            'modelo' => [
                'description' => 'Modelo do veículo.',
                'example' => 'Fiesta',
                'required' => true,
            ],
            'ano' => [
                'description' => 'Ano de fabricação/modelo.',
                'example' => 2024,
                'required' => true,
            ],
            'placa' => [
                'description' => 'Placa padrão Mercosul (normalizada para ABC1D23).',
                'example' => 'ABC1D23',
                'required' => true,
            ],
            'chassi' => [
                'description' => 'Número do chassi (17 caracteres).',
                'example' => '9BWZZZ377VT004251',
                'required' => true,
            ],
            'km' => [
                'description' => 'Quilometragem atual.',
                'example' => 12345,
                'required' => true,
            ],
            'valor_venda' => [
                'description' => 'Preço de venda.',
                'example' => 79990.00,
                'required' => true,
            ],
            'cambio' => [
                'description' => 'Tipo de câmbio.',
                'example' => 'manual',
                'required' => true,
            ],
            'combustivel' => [
                'description' => 'Tipo de combustível.',
                'example' => 'gasolina',
                'required' => true,
            ],
            'cor' => [
                'description' => 'Cor (opcional).',
                'example' => 'preto',
                'required' => false,
            ],
        ];
    }
}
