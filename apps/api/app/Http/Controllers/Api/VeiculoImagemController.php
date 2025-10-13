<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{ Veiculo, ImagemVeiculo };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VeiculoImagemController extends Controller
{
    // POST /veiculos/{veiculo}/imagens
    public function enviar(Request $request, Veiculo $veiculo)
    {
        $this->authorize('update', $veiculo); 

        // validar arquivo (adequar conforme sua regra atual)
        $dados = $request->validate([
            'arquivo' => ['required','file','mimes:jpg,jpeg,png,webp','max:5120'],
        ]);

        $path = $request->file('arquivo')->store('veiculos', 'public');

        // cria relação
        $img = new ImagemVeiculo([
            'path'     => $path,
            'is_cover' => false,
            'order'    => (int) ($veiculo->imagens()->max('order') ?? 0) + 1,
        ]);

        $veiculo->imagens()->save($img);

        // se for a primeira imagem, pode optar por setar como capa
        if ($veiculo->capa()->doesntExist()) {
            $img->is_cover = true;
            $img->save();
        }

        return response()->json([
            'id'   => $img->id,
            'path' => $img->path,
            'is_cover' => $img->is_cover,
        ], 201);
    }

    // PATCH /veiculos/{veiculo}/imagens/{imagem}/capa
    public function definirCapa(Veiculo $veiculo, ImagemVeiculo $imagem)
    {
        $this->authorize('update', $veiculo);

        // garante vínculo correto
        if ($imagem->veiculo_id !== $veiculo->id) {
            abort(404);
        }

        // zera capas anteriores
        $veiculo->imagens()->update(['is_cover' => false]);

        // marca atual
        $imagem->is_cover = true;
        $imagem->save();

        return response()->noContent();
    }

    // DELETE /veiculos/{veiculo}/imagens/{imagem}
    public function excluir(Veiculo $veiculo, ImagemVeiculo $imagem)
    {
        $this->authorize('update', $veiculo);

        if ($imagem->veiculo_id !== $veiculo->id) {
            abort(404);
        }

        Storage::disk('public')->delete($imagem->path);
        $imagem->delete();

        return response()->noContent();
    }
}
