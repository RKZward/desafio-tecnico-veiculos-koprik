<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vehicle;
use App\Http\Resources\VehicleResource;
use App\Http\Requests\VehicleStoreRequest;
use App\Http\Requests\VehicleUpdateRequest;

class VehicleController extends Controller
{
    public function index(Request $r)
    {
        $q = Vehicle::with('cover');
    
        if ($s = $r->query('q')) {
            $q->where(fn($w)=>$w->where('marca','like',"%$s%")
                ->orWhere('modelo','like',"%$s%")
                ->orWhere('placa','like',"%$s%")
                ->orWhere('chassi','like',"%$s%"));
        }
    
        foreach (['marca','modelo','placa'] as $f) if ($v=$r->query($f)) $q->where($f,$v);
    
        if ($sort = $r->query('sort')) {
            foreach (explode(',', $sort) as $s) {
                $dir = str_starts_with($s,'-')?'desc':'asc';
                $col = ltrim($s,'-');
                if (in_array($col,['km','valor_venda','ano','marca','modelo'])) $q->orderBy($col,$dir);
            }
        } else $q->latest('id');
    
        return VehicleResource::collection($q->paginate(min(100,max(5,(int)$r->query('per_page',10)))));
    }
    
    public function store(VehicleStoreRequest $req)
    {
        $data = $req->validated() + ['user_id'=>Auth::id()];
        $v = Vehicle::create($data);
        return new VehicleResource($v->load('cover','images'));
    }
    
    public function show($id)   { return new VehicleResource(Vehicle::with('cover','images')->findOrFail($id)); }
    public function update(VehicleUpdateRequest $r,$id)
    {
        $v = Vehicle::findOrFail($id);
        $this->authorize('update',$v);
        $v->update($r->validated());
        return new VehicleResource($v->fresh('cover','images'));
    }
    public function destroy($id)
    {
        $v = Vehicle::findOrFail($id);
        $this->authorize('delete',$v);
        $v->delete();
        return response()->noContent();
    }
}
