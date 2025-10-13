<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Veiculo;

class VeiculoImagemController extends Controller
{

    public function enviar(Request $req, int $veiculoId)
    {
        $veiculo = Veiculo::findOrFail($veiculoId);
        $this->authorize('update', $veiculo);

        $req->validate([
            'imagens.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $criados = [];
        foreach ($req->file('imagens', []) as $arquivo) {
            $path = $arquivo->store("veiculos/{$veiculo->id}", 'public');
            $criados[] = $veiculo->imagens()->create(['path' => $path]);
        }

        return response()->json([
            'imagens' => collect($criados)->map(fn ($i) => [
                'id'       => $i->id,
                'url'      => asset('storage/' . $i->path),
                'is_cover' => $i->is_cover,
                'order'    => $i->order,
            ]),
        ], 201);
    }

    public function definirCapa(int $veiculoId, int $imagemId)
    {
        $veiculo = Veiculo::findOrFail($veiculoId);
        $this->authorize('update', $veiculo);

        DB::transaction(function () use ($veiculo, $imagemId) {
            $veiculo->imagens()->where('is_cover', true)->update(['is_cover' => false]);
            $veiculo->imagens()->where('id', $imagemId)->update(['is_cover' => true]);
        });

        return response()->json(['ok' => true]);
    }

    public function excluir(int $veiculoId, int $imagemId)
    {
        $veiculo = Veiculo::findOrFail($veiculoId);
        $this->authorize('update', $veiculo);

        $img = $veiculo->imagens()->where('id', $imagemId)->firstOrFail();

        Storage::disk('public')->delete($img->path);
        $img->delete();

        return response()->noContent();
    }
}
