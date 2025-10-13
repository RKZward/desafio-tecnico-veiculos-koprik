<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{ Veiculo, ImagemVeiculo };
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VeiculoImagemController extends Controller
{
public function enviar(Request $request, Veiculo $veiculo)
{
    $this->authorize('update', $veiculo);

    $data = $request->validate([
        'imagens' => ['required','array','min:1','max:10'],
        'imagens.*' => ['file','image','mimes:jpg,jpeg,png,webp','max:4096'],
    ]);

    $saved = [];
    foreach ($data['imagens'] as $file) {
        $path = $file->store("veiculos/{$veiculo->id}", 'public');
        $saved[] = $veiculo->imagens()->create([
            'path' => $path,
            'is_cover' => false,
            'order' => $veiculo->imagens()->max('order') + 1,
        ]);
    }

    return response()->json([
        'imagens' => collect($saved)->map(fn($img)=>[
            'id'=>$img->id,'url'=>asset('storage/'.$img->path),'is_cover'=>$img->is_cover,'order'=>$img->order
        ])
    ], 201);
}

    public function definirCapa(Veiculo $veiculo, ImagemVeiculo $imagem)
    {
        $this->authorize('update', $veiculo);
        abort_if($imagem->veiculo_id !== $veiculo->id, 404);
    
        $veiculo->imagens()->update(['is_cover' => false]);
        $imagem->update(['is_cover' => true]);
    
        return response()->noContent();
    }

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
