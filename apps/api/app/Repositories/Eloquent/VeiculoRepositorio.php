<?php
namespace App\Repositories\Eloquent;

use App\Models\Veiculo;
use App\Repositories\Contratos\VeiculoRepositorioInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VeiculoRepositorio implements VeiculoRepositorioInterface
{
  public function paginar(array $f): LengthAwarePaginator {
    $q = Veiculo::query()->with('capa');

    if (!empty($f['busca'])) {
      $s = $f['busca'];
      $q->where(fn($w)=>$w->where('marca','like',"%$s%")
        ->orWhere('modelo','like',"%$s%")
        ->orWhere('placa','like',"%$s%")
        ->orWhere('chassi','like',"%$s%"));
    }
    foreach (['marca','modelo','placa'] as $c) if (!empty($f[$c])) $q->where($c,$f[$c]);

    if (!empty($f['ordenar'])) {
      foreach (explode(',', $f['ordenar']) as $s) {
        $dir = str_starts_with($s,'-')?'desc':'asc';
        $col = ltrim($s,'-');
        if (in_array($col,['km','valor_venda','ano','marca','modelo'])) $q->orderBy($col,$dir);
      }
    } else $q->latest('id');

    return $q->paginate(min(100,max(5,(int)($f['por_pagina']??10))));
  }

  public function obterPorId(int $id): Veiculo { return Veiculo::with('capa','imagens')->findOrFail($id); }
  public function criar(array $d): Veiculo { return Veiculo::create($d); }
  public function atualizar(Veiculo $v, array $d): Veiculo { $v->update($d); return $v; }
  public function excluir(Veiculo $v): void { $v->delete(); }
}
