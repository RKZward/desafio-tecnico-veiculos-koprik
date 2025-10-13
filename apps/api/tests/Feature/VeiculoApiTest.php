<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Veiculo;
use App\Models\ImagemVeiculo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;  
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VeiculoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_veiculo(): void
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

    public function test_user_can_list_own_veiculo()
{
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $veiculo = Veiculo::factory()->count(2)->create(['user_id' => $user->id]);

    $this->getJson('/api/veiculos')
        ->assertOk()
        ->assertJsonCount(2, 'data');
}

public function test_guest_cannot_access_veiculo_routes()
{
    $this->getJson('/api/veiculos')->assertUnauthorized();
}


public function test_user_can_update_veiculo()
{
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $veiculo = Veiculo::factory()->create(['user_id' => $user->id]);

    $payload = ['km' => 15000, 'valor_venda' => 127000];

    $this->putJson("/api/veiculos/{$veiculo->id}", $payload)
        ->assertOk()
        ->assertJsonPath('data.km', 15000);
}
public function test_user_can_delete_veiculo()
{
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $veiculo = Veiculo::factory()->create(['user_id' => $user->id]);

    $this->deleteJson("/api/veiculos/{$veiculo->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('veiculos', ['id' => $veiculo->id]);
}

public function test_user_cannot_update_others_veiculo()
{
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $other = User::factory()->create();
    $veiculo = Veiculo::factory()->create(['user_id' => $other->id]);

    $this->putJson("/api/veiculos/{$veiculo->id}", ['km' => 9999])
        ->assertForbidden();
}
public function test_user_can_upload_veiculo_image()
{
    Storage::fake('public');
    $user = User::factory()->create();
    Sanctum::actingAs($user);
    $veiculo = Veiculo::factory()->create(['user_id' => $user->id]);

    $file = \Illuminate\Http\UploadedFile::fake()->image('carro.jpg');

    $this->postJson("/api/veiculos/{$veiculo->id}/imagens", [
        'imagem' => $file,
    ])->assertCreated();

    $this->assertTrue(
        Storage::disk('public')->exists("veiculos/{$file->hashName()}")
    );
}
public function test_user_can_set_cover_image()
{
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $veiculo = Veiculo::factory()->create(['user_id' => $user->id]);
    $image = ImagemVeiculo::factory()->create(['veiculo_id' => $veiculo->id, 'is_cover' => false]);

    $this->patchJson("/api/veiculos/{$veiculo->id}/imagens/{$image->id}/capa")
         ->assertOk()
         ->assertJsonPath('data.is_cover', true);
}
public function test_user_can_delete_veiculo_image()
{
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $veiculo = Veiculo::factory()->create(['user_id' => $user->id]);
    $image = ImagemVeiculo::factory()->create(['veiculo_id' => $veiculo->id]);

    $this->deleteJson("/api/veiculos/{$veiculo->id}/imagens/{$image->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('imagens_veiculo', ['id' => $image->id]);
}
public function test_admin_can_update_any_veiculo()
{
    $admin = User::factory()->create(['is_admin' => true]);
    Sanctum::actingAs($admin);

    $veiculo = Veiculo::factory()->create();

    $this->putJson("/api/veiculos/{$veiculo->id}", ['km' => 9999])
        ->assertOk();
}


}
