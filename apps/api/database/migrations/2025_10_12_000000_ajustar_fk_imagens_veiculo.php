<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $exists = collect(DB::select("SHOW INDEX FROM `imagens_veiculo` WHERE Key_name = 'imagens_veiculo_vehicle_id_is_cover_index'"))->isNotEmpty();
        if ($exists) {
            DB::statement("ALTER TABLE `imagens_veiculo` DROP INDEX `imagens_veiculo_vehicle_id_is_cover_index`");
        }

        Schema::table('imagens_veiculo', function (Blueprint $table) {
            try {
                $table->index(['veiculo_id', 'is_cover'], 'imagens_veiculo_veiculo_id_is_cover_idx');
            } catch (\Throwable $e) {
            }
        });
    }

    public function down(): void
    {
        try {
            Schema::table('imagens_veiculo', function (Blueprint $table) {
                $table->dropIndex('imagens_veiculo_veiculo_id_is_cover_idx');
            });
        } catch (\Throwable $e) {
        }
    }
};
