<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // dono do veículo (usuário que criou)
            $table->foreignId('user_id')
                ->constrained()              // ->references('id')->on('users')
                ->cascadeOnDelete();

            $table->string('marca', 60);
            $table->string('modelo', 80);
            $table->unsignedSmallInteger('ano');

            $table->string('placa', 10)->unique();
            $table->string('chassi', 17)->unique();

            $table->unsignedInteger('km')->default(0);
            $table->decimal('valor_venda', 15, 2)->default(0);

            // enums “sem enum nativo” (validado no Request)
            $table->string('cambio', 20);       // manual | automatico | cvt
            $table->string('combustivel', 20);  // gasolina | etanol | flex | diesel | eletrico | hibrido
            $table->string('cor', 30)->nullable();

            $table->timestamps();

            // índices úteis para filtros/ordenação
            $table->index(['marca', 'modelo', 'ano']);
            $table->index(['valor_venda', 'km']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
