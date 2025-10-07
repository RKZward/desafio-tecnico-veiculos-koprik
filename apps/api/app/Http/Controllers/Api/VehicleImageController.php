<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VehicleImageController extends Controller
{
    public function store(Request $r,$vehicleId)
    {
        $v = Vehicle::findOrFail($vehicleId);
        $this->authorize('update',$v);
        $r->validate(['images.*'=>['required','image','max:2048']]);
    
        $created=[];
        foreach($r->file('images',[]) as $file){
            $path = $file->store("vehicles/{$v->id}",'public');
            $created[] = $v->images()->create(['path'=>$path]);
        }
        return response()->json(['images'=>collect($created)->map(fn($i)=>[
            'id'=>$i->id,'url'=>asset('storage/'.$i->path),'is_cover'=>$i->is_cover
        ])],201);
    }
    
    public function setCover($vehicleId,$imageId)
    {
        $v = Vehicle::findOrFail($vehicleId);
        $this->authorize('update',$v);
        DB::transaction(function() use($v,$imageId){
            $v->images()->where('is_cover',true)->update(['is_cover'=>false]);
            $v->images()->where('id',$imageId)->update(['is_cover'=>true]);
        });
        return response()->json(['ok'=>true]);
    }
    
    public function destroy($vehicleId,$imageId)
    {
        $v = Vehicle::findOrFail($vehicleId);
        $this->authorize('update',$v);
        $img = $v->images()->where('id',$imageId)->firstOrFail();
        Storage::disk('public')->delete($img->path);
        $img->delete();
        return response()->noContent();
    }
    
}
