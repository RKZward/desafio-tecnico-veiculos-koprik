<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Veiculo;
use App\Http\Resources\VeiculoRecurso;
use App\Http\Requests\VeiculoArmazenarRequest;
use App\Http\Requests\VeiculoAtualizarRequest;
use App\Services\VeiculoServico;

class VeiculoController extends Controller
{
    public function __construct(private VeiculoServico $servico) {}
    public function index(Request $req)
{
    $filtros = $req->only(['busca','marca','modelo','placa','ordenar','por_pagina']);
    return VeiculoRecurso::collection($this->servico->listarPaginado($filtros));
}

public function store(VeiculoArmazenarRequest $request)
{
    $dados = $request->validated();
    $dados['user_id'] = Auth::id();
    $v = Veiculo::create($dados);
    

    // return new \App\Http\Resources\VeiculoRecurso($v->load('capa','imagens'));
}

public function show(int $id)
{
    return new VeiculoRecurso($this->servico->obter($id));
}

public function update(VeiculoAtualizarRequest $req, int $id)
{
    $v = Veiculo::findOrFail($id);
    $this->authorize('update', $v);
    $v = $this->servico->atualizar($v, $req->validated());
    return new VeiculoRecurso($v->fresh('capa','imagens'));
}

public function destroy(int $id)
{
    $v = Veiculo::findOrFail($id);
    $this->authorize('delete', $v);
    $this->servico->excluir($v);
    return response()->noContent();
}

}
