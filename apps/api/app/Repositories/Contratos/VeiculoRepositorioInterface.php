<?php
namespace App\Repositories\Contratos;
use App\Models\Veiculo;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface VeiculoRepositorioInterface {
  public function paginar(array $filtros): LengthAwarePaginator;
  public function obterPorId(int $id): Veiculo;
  public function criar(array $dados): Veiculo;
  public function atualizar(Veiculo $v, array $dados): Veiculo;
  public function excluir(Veiculo $v): void;
}
