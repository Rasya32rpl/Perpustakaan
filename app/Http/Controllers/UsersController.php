<?php

namespace App\Http\Controllers;
use App\Models\users; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class UsersController extends Controller
{
    public function updateusers(Request $req, $id)
{
    $validaator = Validator::make($req->all(), [
        'Id_buku'=>'required',
        'Nama'=>'required',
        'Alamat'=>'required',
        'No_tlp'=>'required',
        'Role'=>'required',
]);

if($validaator->fails()){
    return response()->json($validaator->errors(),400);
}

$ubah = users::where('Id',$id)->update([
            'Id_buku' =>$req->get('Id_buku'),
            'Nama' =>$req->get('Nama'),
            'Alamat' =>$req->get('Alamat'),
            'No_tlp' =>$req->get('No_tlp'),
            'Role' =>$req->get('Role'),
        ]);
    
         if($ubah){
             return Response()->json(['status'=>true, 'message' => 'Sukses update users']);
         }else {
            return Response()->json(['status'=>false, 'message' => 'Gagal update users']);
    }
    }
    public function getusers()
    {
        $dt_users=users::get();
        return response()->json($dt_users);
    }
    public function getusersid($id)
{
    $dt_users = users::where('Id', $id)->first();
    return response()->json($dt_users);
}
    public function createusers(Request $req)
    {
        $validator = Validator::make($req->all(),[
        'Id_buku'=>'required',
        'Nama'=>'required',
        'Alamat'=>'required',
        'No_tlp'=>'required',
        'Role'=>'required',
        ]);
        if($validator->fails()){
        return Response()->json($validator->errors()->toJson());
        }
        $save = users::create([
        'Id_buku' =>$req->get('Id_buku'),
        'Nama' =>$req->get('Nama'),
        'Alamat' =>$req->get('Alamat'),
        'No_tlp' =>$req->get('No_tlp'),
        'Role' =>$req->get('Role'),
        ]);
        if($save){
        return Response()->json(['status'=>true, 'message' => 'Sukses
        menambah users']);
        }else {
        return Response()->json(['status'=>false, 'message' => 'Gagal
        menambah users']);
        }
    }
    public function deleteusers($id){
        $users = users::where('Id', $id);
        if(!$users) {
            return response()->json(['status'=>false, 'message'=> "Users dengan id $id tidak ditemukan"],404);
        }
        $users->delete();
        return response()->json(['status'=>true, 'message'=>'Users berhasil dihapus']);
    }
}
