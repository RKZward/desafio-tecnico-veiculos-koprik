<?php
namespace App\Services;

use App\Models\Veiculo;
use App\Repositories\Contratos\VeiculoRepositorioInterface;
use Illuminate\Support\Facades\Auth;

class VeiculoServico
{
  public function __construct(private VeiculoRepositorioInterface $repo) {}

  public function listarPaginado(array $f) { return $this->repo->paginar($f); }
  public function criar(array $d): Veiculo { $d['usuario_id']=Auth::id(); return $this->repo->criar($d); }
  public function obter(int $id): Veiculo { return $this->repo->obterPorId($id); }
  public function atualizar(Veiculo $v,array $d): Veiculo { return $this->repo->atualizar($v,$d); }
  public function excluir(Veiculo $v): void { $this->repo->excluir($v); }
}
