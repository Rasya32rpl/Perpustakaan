<?php

namespace App\Http\Controllers;
use App\Models\Buku; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class BukuController extends Controller
{
    public function getbuku()
    {
        $dt_buku=buku::get();
        return response()->json($dt_buku);
    }
    public function getbukuid($id)
{
    $dt_buku = Buku::where('id_buku', $id)->first();
    return response()->json($dt_buku);
}
public function updatebuku(Request $req, $id)
{
    $validaator = Validator::make($req->all(), [
        'judul_buku' => 'required',
        'penulis' => 'required',
        'penerbit' => 'required',
        'kategori' => 'required',
]);

if($validaator->fails()){
    return response()->json($validaator->errors(),400);
}

$ubah = buku::where('id_buku',$id)->update([
            'judul_buku' =>$req->get('judul_buku'),
            'penulis' =>$req->get('penulis'),
            'penerbit' =>$req->get('penerbit'),
            'kategori' =>$req->get('kategori'),
        ]);
    
         if($ubah){
             return Response()->json(['status'=>true, 'message' => 'Sukses update buku']);
         }else {
            return Response()->json(['status'=>false, 'message' => 'Gagal update buku']);
    }
    }
    public function createbuku(Request $req)
    {
        $validator = Validator::make($req->all(),[
        'judul_buku'=>'required',
        'penulis'=>'required',
        'penerbit'=>'required',
        'kategori'=>'required',
        ]);
        if($validator->fails()){
        return Response()->json($validator->errors()->toJson());
        }
        $save = buku::create([
        'judul_buku' =>$req->get('judul_buku'),
        'penulis' =>$req->get('penulis'),
        'penerbit' =>$req->get('penerbit'),
        'kategori' =>$req->get('kategori'),
        ]);
        if($save){
        return Response()->json(['status'=>true, 'message' => 'Sukses
        menambah buku']);
        }else {
        return Response()->json(['status'=>false, 'message' => 'Gagal
        menambah buku']);
        }
    }
    public function deletebuku($id){
        $buku = buku::where('id_buku', $id);
        if(!$buku) {
            return response()->json(['status'=>false, 'message'=> "Buku dengan id $id tidak ditemukan"],404);
        }
        $buku->delete();
        return response()->json(['status'=>true, 'message'=>'Buku berhasil dihapus']);
    }
}
