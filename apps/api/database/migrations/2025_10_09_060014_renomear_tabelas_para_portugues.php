<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (Schema::hasTable('vehicles')) Schema::rename('vehicles', 'veiculos');
        if (Schema::hasTable('vehicle_images')) Schema::rename('vehicle_images', 'imagens_veiculo');
    }
    public function down(): void {
        if (Schema::hasTable('veiculos')) Schema::rename('veiculos', 'vehicles');
        if (Schema::hasTable('imagens_veiculo')) Schema::rename('imagens_veiculo', 'vehicle_images');
    }
};
