<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Veiculo;
use App\Http\Resources\VeiculoRecurso;
use App\Http\Requests\{ VeiculoArmazenarRequest, VeiculoAtualizarRequest };

class VeiculoController extends Controller
{
    public function index(Request $r)
    {
        $q = Veiculo::query()->with(['capa','imagens']);

        if ($s = $r->query('busca')) {
            $q->where(function ($w) use ($s) {
                $w->where('marca','like',"%{$s}%")
                  ->orWhere('modelo','like',"%{$s}%")
                  ->orWhere('placa','like',"%{$s}%")
                  ->orWhere('chassi','like',"%{$s}%");
            });
        }

        foreach (['marca','modelo','placa'] as $f) {
            if ($v = $r->query($f)) $q->where($f, $v);
        }

        if ($sort = $r->query('ordenar')) {
            foreach (explode(',', $sort) as $s) {
                $dir = str_starts_with($s,'-') ? 'desc' : 'asc';
                $col = ltrim($s,'-');
                if (in_array($col, ['km','valor_venda','ano','marca','modelo'])) {
                    $q->orderBy($col, $dir);
                }
            }
        } else {
            $q->latest('id');
        }

        $porPagina = (int) $r->query('por_pagina', 10);
        $porPagina = max(5, min(100, $porPagina));

        return VeiculoRecurso::collection($q->paginate($porPagina));
    }

    public function store(VeiculoArmazenarRequest $req)
    {
        $dados = $req->validated();
        $dados['user_id'] = Auth::id(); // importante

        $v = Veiculo::create($dados);
        return new VeiculoRecurso($v->load('capa','imagens'));
    }

    // Binding: /veiculos/{veiculo}
    public function show(Veiculo $veiculo)
    {
        return new VeiculoRecurso($veiculo->load('capa','imagens'));
    }

    public function update(VeiculoAtualizarRequest $req, Veiculo $veiculo)
    {
        $this->authorize('update', $veiculo);

        $veiculo->update($req->validated());
        return new VeiculoRecurso($veiculo->fresh('capa','imagens'));
    }

    public function destroy(Veiculo $veiculo)
    {
        $this->authorize('delete', $veiculo);

        $veiculo->delete();
        return response()->noContent();
    }
}
