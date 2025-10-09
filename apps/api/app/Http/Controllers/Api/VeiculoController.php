<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Veiculo;
use App\Http\Resources\VeiculoRecurso;
use App\Http\Requests\VeiculoArmazenarRequest;
use App\Http\Requests\VeiculoAtualizarRequest;

class VeiculoController extends Controller
{
    public function index(Request $req)
    {
        $query = Veiculo::with('capa');

        // busca livre: ?busca=... (ou ?q=...)
        $busca = $req->query('busca', $req->query('q'));
        if ($busca) {
            $s = $busca;
            $query->where(fn($w) => $w
                ->where('marca',  'like', "%{$s}%")
                ->orWhere('modelo','like', "%{$s}%")
                ->orWhere('placa', 'like', "%{$s}%")
                ->orWhere('chassi','like', "%{$s}%")
            );
        }

        // filtros diretos
        foreach (['marca','modelo','placa'] as $campo) {
            if ($v = $req->query($campo)) {
                $query->where($campo, $v);
            }
        }

        // ordenação: ?ordenar=km,-valor_venda,ano (ou ?sort=...)
        $ordenar = $req->query('ordenar', $req->query('sort'));
        if ($ordenar) {
            foreach (explode(',', $ordenar) as $s) {
                $dir = str_starts_with($s, '-') ? 'desc' : 'asc';
                $col = ltrim($s, '-');
                if (in_array($col, ['km','valor_venda','ano','marca','modelo'])) {
                    $query->orderBy($col, $dir);
                }
            }
        } else {
            $query->latest('id');
        }

        // paginação: ?por_pagina=10 (ou ?per_page=10)
        $porPagina = (int) ($req->query('por_pagina', $req->query('per_page', 10)));
        $porPagina = min(100, max(5, $porPagina));

        return VeiculoRecurso::collection($query->paginate($porPagina));
    }

    public function store(VeiculoArmazenarRequest $req)
    {
        $dados = $req->validated() + ['usuario_id' => Auth::id()];
        $veiculo = Veiculo::create($dados);

        return new VeiculoRecurso($veiculo->load('capa','imagens'));
    }

    public function show(int $id)
    {
        $veiculo = Veiculo::with('capa','imagens')->findOrFail($id);
        return new VeiculoRecurso($veiculo);
    }

    public function update(VeiculoAtualizarRequest $req, int $id)
    {
        $veiculo = Veiculo::findOrFail($id);
        $this->authorize('update', $veiculo);

        $veiculo->update($req->validated());

        return new VeiculoRecurso($veiculo->fresh('capa','imagens'));
    }

    public function destroy(int $id)
    {
        $veiculo = Veiculo::findOrFail($id);
        $this->authorize('delete', $veiculo);

        $veiculo->delete();

        return response()->noContent();
    }
}
