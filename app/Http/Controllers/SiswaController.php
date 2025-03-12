<?php
namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Siswa;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SiswaController extends Controller
{
    // 1. Perbaikan pada method getsiswaid
    // Menggunakan select() untuk mengambil kolom yang diinginkan dan menghindari bentrok antara id dari tabel siswa dan kelas.
    public function getsiswaid($id)
    {
        $dt_siswa = Siswa::join('kelas', 'siswa.id_kelas', '=', 'kelas.id')
            ->select(
                'siswa.id as id_siswa',      // alias untuk id siswa
                'siswa.nama_siswa',
                'siswa.tanggal_lahir',
                'siswa.gender',
                'siswa.alamat',
                'siswa.no_tlp',
                'siswa.id_kelas',            // id kelas tetap diambil dari tabel siswa
                'kelas.nama_kelas',
                'kelas.kelompok'
            )
            ->where('siswa.id', $id)
            ->first();  // Menggunakan first() untuk mengambil satu record

        return response()->json($dt_siswa);
    }

    public function updatesiswa(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'nama_siswa'    => 'required',
            'tanggal_lahir' => 'required',
            'gender'        => 'required',
            'alamat'        => 'required',
            'no_tlp'        => 'required',
            'id_kelas'      => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $ubah = Siswa::where('id', $id)->update([
            'nama_siswa'    => $req->get('nama_siswa'),
            'tanggal_lahir' => $req->get('tanggal_lahir'),
            'gender'        => $req->get('gender'),
            'alamat'        => $req->get('alamat'),
            'no_tlp'        => $req->get('no_tlp'),
            'id_kelas'      => $req->get('id_kelas')
        ]);

        if ($ubah) {
            return response()->json(['status' => true, 'message' => 'Sukses update siswa']);
        } else {
            return response()->json(['status' => false, 'message' => 'Gagal update siswa']);
        }
    }

    public function deletesiswa($id)
{
    // Cek apakah siswa masih memiliki peminjaman aktif (belum mengembalikan buku)
    $siswaMasihMeminjam = Peminjaman::where('id_siswa', $id)
    ->where('tanggal_kembali', '>=', Carbon::today()) // Cek apakah tanggal kembali masih di masa depan
    ->exists();

    // Cek apakah siswa sudah dalam daftar pengembalian
    $siswaDalamPengembalian = Pengembalian::whereHas('peminjaman', function ($query) use ($id) {
        $query->where('id_siswa', $id);
    })->exists();

    if ($siswaMasihMeminjam) {
        return response()->json([
            'status' => false,
            'message' => 'Siswa masih memiliki buku yang dipinjam dan tidak dapat dihapus!'
        ], 400);
    }

    if ($siswaDalamPengembalian) {
        return response()->json([
            'status' => false,
            'message' => 'Siswa masih berada dalam daftar pengembalian dan tidak dapat dihapus!'
        ], 400);
    }

    // Jika tidak ada peminjaman atau pengembalian terkait, hapus siswa
    $siswa = Siswa::find($id);

    if (!$siswa) {
        return response()->json([
            'status' => false,
            'message' => "Siswa dengan id $id tidak ditemukan"
        ], 404);
    }

    $siswa->delete();
    return response()->json([
        'status' => true,
        'message' => 'Siswa berhasil dihapus'
    ]);
}



    public function getsiswa()
{
    $dt_siswa = Siswa::join('kelas', 'siswa.id_kelas', '=', 'kelas.id')
        ->select(
            'siswa.id as id_siswa', // alias untuk id siswa agar tidak tertukar dengan id kelas
            'siswa.nama_siswa',
            'siswa.tanggal_lahir',
            'siswa.gender',
            'siswa.alamat',
            'siswa.no_tlp',
            'siswa.id_kelas',
            'kelas.nama_kelas',
            'kelas.kelompok'
        )
        ->get();

    return response()->json($dt_siswa);
}


    // 2. Perubahan pada method createsiswa untuk menampilkan data siswa yang baru dibuat beserta pesan sukses.
    public function createsiswa(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'nama_siswa'    => 'required',
            'alamat'        => 'required',
            'tanggal_lahir' => 'required',
            'gender'        => 'required',
            'no_tlp'        => 'required',
            'id_kelas'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson());
        }

        $siswa = Siswa::create([
            'nama_siswa'    => $req->get('nama_siswa'),
            'tanggal_lahir' => $req->get('tanggal_lahir'),
            'alamat'        => $req->get('alamat'),
            'gender'        => $req->get('gender'),
            'no_tlp'        => $req->get('no_tlp'),
            'id_kelas'      => $req->get('id_kelas'),
        ]);

        if ($siswa) {
            return response()->json([
                'status'  => true,
                'message' => 'Sukses menambah siswa',
                'data'    => $siswa  // menampilkan data siswa yang baru ditambahkan
            ]);
        } else {
            return response()->json(['status' => false, 'message' => 'Gagal menambah siswa']);
        }
    }   
}
