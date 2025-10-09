<?php

namespace App\Services;

use App\Models\Veiculo;
use App\Repositories\Contratos\VeiculoRepositorioInterface;
use Illuminate\Support\Facades\Auth;

class VeiculoServico
{
    public function __construct(private VeiculoRepositorioInterface $repo) {}

    public function listarPaginado(array $filtros)
    {
        return $this->repo->paginar($filtros);
    }

    public function criar(array $dados): Veiculo
    {
        $dados['usuario_id'] = Auth::id();
        return $this->repo->criar($dados);
    }

    public function obter(int $id): Veiculo
    {
        return $this->repo->obterPorId($id);
    }

    public function atualizar(Veiculo $veiculo, array $dados): Veiculo
    {
        return $this->repo->atualizar($veiculo, $dados);
    }

    public function excluir(Veiculo $veiculo): void
    {
        $this->repo->excluir($veiculo);
    }
}
