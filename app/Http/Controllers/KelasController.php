<?php 
namespace App\Http\Controllers; 
use App\Models\Kelas; 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Facades\Hash; 
 
class KelasController extends Controller 
{ 
    public function createkelas(Request $req) 
    { 
        $validator = Validator::make($req->all(),[ 
            'nama_kelas'=>'required', 
            'kelompok'=>'required', 
        ]); 
        if($validator->fails()){ 
            return Response()->json($validator->errors()->toJson()); 
        } 
        $save = kelas::create([ 
            'nama_kelas'    =>$req->get('nama_kelas'), 
            'kelompok'        =>$req->get('kelompok'), 
  
 
        ]); 
        if($save){ 
            return Response()->json(['status'=>true, 'message' => 'Sukses 
menambah Kelas']); 
}else { 
return Response()->json(['status'=>false, 'message' => 'Gagal 
menambah Kelas']); 
} 
} 
}