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

    public function show(int $id)
    {
        $v = Veiculo::with('capa','imagens')->findOrFail($id);
        return new VeiculoRecurso($v);
    }

    public function update(VeiculoAtualizarRequest $req, int $id)
    {
        $v = Veiculo::findOrFail($id);
        $this->authorize('update', $v);

        $v->update($req->validated());
        return new VeiculoRecurso($v->fresh('capa','imagens'));
    }

    public function destroy(int $id)
    {
        $v = Veiculo::findOrFail($id);
        $this->authorize('delete', $v);
        $v->delete();
        return response()->noContent();
    }
}
