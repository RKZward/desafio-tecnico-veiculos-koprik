<?PHP
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration {
    public function up(): void {
        Schema::table('imagens_veiculo', function (Blueprint $table) {
            if (Schema::hasColumn('imagens_veiculo', 'vehicle_id')) {
                $table->renameColumn('vehicle_id', 'veiculo_id');
            }
            // re-crie índices coerentes
            $table->dropIndex(['vehicle_id','is_cover']); // se existir
            $table->index(['veiculo_id','is_cover']);
        });
    }
    public function down(): void {
        Schema::table('imagens_veiculo', function (Blueprint $table) {
            if (Schema::hasColumn('imagens_veiculo', 'veiculo_id')) {
                $table->renameColumn('veiculo_id', 'vehicle_id');
            }
            $table->dropIndex(['veiculo_id','is_cover']);
            $table->index(['vehicle_id','is_cover']);
        });
    }
};
