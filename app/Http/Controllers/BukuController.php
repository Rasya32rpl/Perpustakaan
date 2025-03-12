<?php

namespace App\Http\Controllers;

use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Buku;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function getbuku()
    {
        // Mengambil semua data buku
        $dt_buku = Buku::get();
        return response()->json($dt_buku);
    }

    public function getbukuid($id)
    {
        // Mengambil satu buku berdasarkan id_buku
        $dt_buku = Buku::where('id_buku', $id)->first();
        return response()->json($dt_buku);
    }

    public function updatebuku(Request $req, $id)
    {
        // Validasi input; gambar_buku bersifat opsional
        $validator = Validator::make($req->all(), [
            'judul_buku' => 'required',
            'penulis'    => 'required',
            'penerbit'   => 'required',
            'kategori'   => 'required',
            // Jika ingin menambahkan validasi file, bisa tambahkan aturan berikut (opsional):
            'gambar_buku' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        // Cek apakah ada file gambar yang diupload
        if ($req->hasFile('gambar_buku')) {
            $file = $req->file('gambar_buku');
            $fileName = time() . '_' . $file->getClientOriginalName();
            // Pastikan folder public/uploads sudah ada dan memiliki permission yang tepat
            $file->move(public_path('uploads'), $fileName);
            $gambar_buku = url('uploads/' . $fileName);
        } else {
            $gambar_buku = null; // Jika tidak ada file baru, jangan update gambar_buku
        }

        // Data update
        $dataUpdate = [
            'judul_buku' => $req->get('judul_buku'),
            'penulis'    => $req->get('penulis'),
            'penerbit'   => $req->get('penerbit'),
            'kategori'   => $req->get('kategori'),
        ];

        // Jika ada file gambar baru, tambahkan ke data update
        if ($gambar_buku) {
            $dataUpdate['gambar_buku'] = $gambar_buku;
        }

        $ubah = Buku::where('id_buku', $id)->update($dataUpdate);

        if($ubah){
            return response()->json(['status' => true, 'message' => 'Sukses update buku']);
        } else {
            return response()->json(['status' => false, 'message' => 'Gagal update buku']);
        }
    }

    public function createbuku(Request $req)
    {
        // Validasi input; gambar_buku bersifat opsional
        $validator = Validator::make($req->all(), [
            'judul_buku' => 'required',
            'penulis'    => 'required',
            'penerbit'   => 'required',
            'kategori'   => 'required',
            // Tambahkan validasi file jika diinginkan:
            'gambar_buku' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson());
        }

        // Cek apakah ada file gambar yang diupload
        if ($req->hasFile('gambar_buku')) {
            $file = $req->file('gambar_buku');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $fileName);
            $gambar_buku = url('uploads/' . $fileName);
        } else {
            $gambar_buku = null;
        }

        $save = Buku::create([
            'judul_buku'  => $req->get('judul_buku'),
            'penulis'     => $req->get('penulis'),
            'penerbit'    => $req->get('penerbit'),
            'kategori'    => $req->get('kategori'),
            'gambar_buku' => $gambar_buku,
        ]);

        if($save){
            return response()->json([
                'status'  => true,
                'message' => 'Sukses menambah buku',
                'data'    => $save  // menampilkan data buku yang baru ditambahkan
            ]);
        } else {
            return response()->json(['status' => false, 'message' => 'Gagal menambah buku']);
        }
    }

    public function deletebuku($id)
{
    // Cek apakah buku masih dalam daftar peminjaman
    $bukuDipinjam = DetailPeminjaman::where('id_buku', $id)
        ->whereHas('peminjaman', function ($query) {
            $query->where('tanggal_kembali', '>=', now());
        })
        ->exists();

    if ($bukuDipinjam) {
        return response()->json([
            'status' => false,
            'message' => 'Buku masih berada dalam daftar peminjaman dan tidak dapat dihapus!'
        ], 400);
    }

    // Cari buku yang akan dihapus
    $buku = Buku::where('id_buku', $id)->first();

    // Jika buku tidak ditemukan
    if (!$buku) {
        return response()->json([
            'status' => false,
            'message' => "Buku dengan id $id tidak ditemukan"
        ], 404);
    }

    // Hapus buku
    Buku::where('id_buku', $id)->delete(); 

    return response()->json([
        'status' => true,
        'message' => 'Buku berhasil dihapus'
    ]);
}

}

