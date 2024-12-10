<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class PeminjamanController extends Controller
{
    public function createpeminjaman(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id_siswa' => 'required',
            // 'id_kelas' => 'required', (sudah tidak digunakan sesuai konteks proyek Anda)
            'id_buku' => 'required', // (jika ini masih ada di database, tambahkan validasi lain)
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson());
        }

        $tenggat = Carbon::now()->addDays(4); // Menambahkan 4 hari dari tanggal sekarang
        $save = Peminjaman::create([
            'id_siswa' => $req->get('id_siswa'),
            // 'id_kelas' => $req->get('id_kelas'), (sudah dihapus dari proyek Anda)
            'id_buku' => $req->get('id_buku'),
            'tanggal_pinjam' => Carbon::now()->format('Y-m-d H:i:s'),
            'tanggal_kembali' => $tenggat,
            'status' => 'Dipinjam',
        ]);

        if ($save) {
            return response()->json(['status' => true, 'message' => 'Sukses Menambah Peminjaman']);
        } else {
            return response()->json(['status' => false, 'message' => 'Gagal Menambah Peminjaman']);
        }
    }

    public function getpeminjaman()
    {
        $peminjaman = Peminjaman::all();

        return response()->json(['status' => true, 'data' => $peminjaman]);
    }

    public function getpeminjamanid($id)
    {
        $peminjaman = Peminjaman::find($id);

        if ($peminjaman) {
            return response()->json(['status' => true, 'data' => $peminjaman]);
        } else {
            return response()->json(['status' => false, 'message' => 'Peminjaman tidak ditemukan']);
        }
    }
}
