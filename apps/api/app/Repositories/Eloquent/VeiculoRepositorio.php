<?php

namespace App\Repositories\Eloquent;

use App\Models\Veiculo;
use App\Repositories\Contratos\VeiculoRepositorioInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class VeiculoRepositorio implements VeiculoRepositorioInterface
{
    public function paginar(array $f): LengthAwarePaginator
    {
        $q = Veiculo::query()->with('capa');

        if (!empty($f['busca'])) {
            $s = $f['busca'];
            $q->where(fn($w)=>$w->where('marca','like',"%$s%")
                ->orWhere('modelo','like',"%$s%")
                ->orWhere('placa','like',"%$s%")
                ->orWhere('chassi','like',"%$s%"));
        }

        foreach (['marca','modelo','placa'] as $campo) {
            if (!empty($f[$campo])) $q->where($campo, $f[$campo]);
        }

        if (!empty($f['ordenar'])) {
            foreach (explode(',', $f['ordenar']) as $s) {
                $dir = str_starts_with($s,'-') ? 'desc' : 'asc';
                $col = ltrim($s,'-');
                if (in_array($col, ['km','valor_venda','ano','marca','modelo'])) {
                    $q->orderBy($col,$dir);
                }
            }
        } else {
            $q->latest('id');
        }

        $porPagina = min(100, max(5, (int)($f['por_pagina'] ?? 10)));
        return $q->paginate($porPagina);
    }

    public function obterPorId(int $id): Veiculo
    {
        return Veiculo::with(['capa','imagens'])->findOrFail($id);
    }

    public function criar(array $dados): Veiculo
    {
        return Veiculo::create($dados);
    }

    public function atualizar(Veiculo $veiculo, array $dados): Veiculo
    {
        $veiculo->update($dados);
        return $veiculo;
    }

    public function excluir(Veiculo $veiculo): void
    {
        $veiculo->delete();
    }
}
