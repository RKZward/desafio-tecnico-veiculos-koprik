<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;   // <— importa Sanctum
use Tests\TestCase;

class VehicleApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_vehicle(): void
    {
        $user = User::factory()->create();

        // autentica no guard sanctum
        Sanctum::actingAs($user, ['*']);

        $payload = [
            'marca' => 'Fiat', 'modelo' => 'Pulse', 'ano' => 2023,
            'placa' => 'ABC1D23', 'chassi' => '1HGCM82633A004352',
            'km' => 10000, 'valor_venda' => 129900,
            'cambio' => 'automatico', 'combustivel' => 'flex', 'cor' => 'branco',
        ];

        $this->postJson('/api/veiculos', $payload)
            ->assertCreated()
            ->assertJsonPath('data.placa', 'ABC1D23');

        $this->assertDatabaseHas('veiculos', [
            'placa' => 'ABC1D23', 'user_id' => $user->id,
        ]);
    }

    public function test_validation_rejects_bad_plate(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $bad = [
            'marca'=>'Fiat','modelo'=>'Pulse','ano'=>2023,
            'placa'=>'AAA-1234', // formato antigo
            'chassi'=>'1HGCM82633A004352','km'=>0,'valor_venda'=>100000,
            'cambio'=>'manual','combustivel'=>'gasolina','cor'=>'preto',
        ];

        $this->postJson('/api/veiculos', $bad)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['placa']);
    }
}
