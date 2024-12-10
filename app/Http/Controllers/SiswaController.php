<?php
namespace App\Http\Controllers;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class SiswaController extends Controller
{
    public function getsiswaid($id)
    {
        $dt_siswa = siswa::join('kelas', 'siswa.id_kelas', '=', 'kelas.id')
                     ->where('siswa.id', $id)
                     ->first();  // Menggunakan first() untuk mengambil satu record
        return response()->json($dt_siswa);
    }

    public function updatesiswa(Request $req, $id)
    {
        $validaator = Validator::make($req->all(), [
            'nama_siswa' => 'required',
            'tanggal_lahir' => 'required',
            'gender' => 'required',
            'alamat' => 'required',
            'no_tlp' => 'required',
            'id_kelas' => 'required'
    ]);

    if($validaator->fails()){
        return response()->json($validaator->errors(),400);
    }

    $ubah = siswa::where('id',$id)->update([
                'nama_siswa' => $req->get('nama_siswa'),
                'tanggal_lahir' => $req->get('tanggal_lahir'),
                'gender' => $req->get('gender'),
                'alamat' => $req->get('alamat'),
                'no_tlp' => $req->get('no_tlp'),
                'id_kelas' => $req->get('id_kelas')
            ]);
        
             if($ubah){
                 return Response()->json(['status'=>true, 'message' => 'Sukses update siswa']);
             }else {
                return Response()->json(['status'=>false, 'message' => 'Gagal update siswa']);
        }
        }

    public function deletesiswa($id){
        $siswa = siswa::find($id);
        if(!$siswa) {
            return response()->json(['status'=>false, 'message'=> "Siswa dengan id $id tidak ditemukan"],404);
        }
        $siswa->delete();
        return response()->json(['status'=>true, 'message'=>'Siswa berhasil dihapus']);
    }

    public function getsiswa()
    {
        $dt_siswa=siswa::join('kelas','siswa.id_kelas','=','kelas.id') ->get();
        return response()->json($dt_siswa);
    }
    public function createsiswa(Request $req)
    {
        $validator = Validator::make($req->all(),[
        'nama_siswa'=>'required',
        'alamat'=>'required',
        'tanggal_lahir'=>'required',
        'gender'=>'required',
        'no_tlp'=>'required',
        'id_kelas' => 'required',
        ]);
        if($validator->fails()){
        return Response()->json($validator->errors()->toJson());
        }
        $save = siswa::create([
        'nama_siswa' =>$req->get('nama_siswa'),
        'tanggal_lahir' =>$req->get('tanggal_lahir'),
        'alamat' =>$req->get('alamat'),
        'gender' =>$req->get('gender'),
        'no_tlp' =>$req->get('no_tlp'),
        'id_kelas' =>$req->get('id_kelas'),
        ]);
        if($save){
        return Response()->json(['status'=>true, 'message' => 'Sukses
        menambah siswa']);
        }else {
        return Response()->json(['status'=>false, 'message' => 'Gagal
        menambah siswa']);
        }
    }   
}