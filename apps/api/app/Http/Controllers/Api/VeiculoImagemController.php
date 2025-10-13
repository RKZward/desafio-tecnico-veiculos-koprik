<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{ Veiculo };
use App\Models\{ ImagemVeiculo };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class VeiculoImagemController extends Controller
{
public function enviar(Request $request, Veiculo $veiculo)
{
    $this->authorize('update', $veiculo);

    $request->validate([
        'files' => ['required','array','min:1','max:10'],
        'files.*' => ['file','image','mimes:jpg,jpeg,png,webp','max:5120']
    ]);

    $saved = [];
    foreach ($request->file('files') as $file) {
        $path = $file->store("veiculos/{$veiculo->id}", 'public');
        $saved[] = $veiculo->imagens()->create([
            'path' => $path,
            'is_cover' => false,
            'order' => ($veiculo->imagens()->max('order') ?? 0) + 1,
        ]);
    }

    return response()->json([
        'data' => collect($saved)->map(fn($img) => [
            'id'       => $img->id,
            'url'      => Storage::disk('public')->url($img->path),
            'is_cover' => (bool)$img->is_cover,
            'order'    => $img->order,
        ]),
    ], 201);
}

    public function definirCapa(Veiculo $veiculo, ImagemVeiculo $imagem)
    {
        if ($imagem->veiculo_id !== $veiculo->id) {
            throw ValidationException::withMessages(['imagem' => ['Imagem não pertence ao veículo.']]);
        }
        ImagemVeiculo::where('veiculo_id', $veiculo->id)->update(['is_cover' => false]);
        $imagem->update(['is_cover' => true]);

        return response()->json([
            'id'       => $imagem->id,
            'url'      => Storage::disk('public')->url($imagem->path),
            'is_cover' => true,
            'order'    => $imagem->order,
        ]);
    }

    public function excluir(Veiculo $veiculo, ImagemVeiculo $imagem)
    {
        if ($imagem->veiculo_id !== $veiculo->id) {
            throw ValidationException::withMessages(['imagem' => ['Imagem não pertence ao veículo.']]);
        }

        Storage::disk('public')->delete($imagem->path);
        $imagem->delete();

        return response()->noContent();
    }
}
