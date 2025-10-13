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
        $q = Veiculo::query()->with(['capa'])->when($r->filled('search'), function($q) use ($r) {
            $s = $r->string('search');
            $q->where(function($qq) use ($s) {
                $qq->where('marca','like',"%{$s}%")
                   ->orWhere('modelo','like',"%{$s}%")
                   ->orWhere('placa','like',"%{$s}%");
            });
        })->when($r->filled('marca'), fn($q)=>$q->where('marca',$r->marca))
          ->when($r->filled('ano_min'), fn($q)=>$q->where('ano','>=',(int)$r->ano_min))
          ->when($r->filled('ano_max'), fn($q)=>$q->where('ano','<=',(int)$r->ano_max));
    
        $sort = in_array($r->get('sort'), ['mais_novos','mais_antigos','preco_asc','preco_desc'])
          ? $r->get('sort') : 'mais_novos';
    
        match ($sort) {
          'mais_antigos' => $q->orderBy('created_at','asc'),
          'preco_asc'    => $q->orderBy('valor_venda','asc'),
          'preco_desc'   => $q->orderBy('valor_venda','desc'),
          default        => $q->orderBy('created_at','desc')
        };
    
        $perPage = min(max((int)$r->get('per_page', 10), 5), 50);
        $page = $q->paginate($perPage)->withQueryString();
    
        return VeiculoRecurso::collection($page);
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
