<?php

// app/Http/Controllers/PeminjamanController.php
namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\DetailPeminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PeminjamanController extends Controller
{
    public function createpeminjaman(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id_siswa' => 'required|exists:siswa,id',
            'buku' => 'required|array',
            'buku.*.id_buku' => 'required|integer|exists:buku,id_buku',
            'buku.*.qty' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 400);
        }

        DB::beginTransaction(); // Start transaksi database

        try {
            // Buat peminjaman baru
            $tenggat = Carbon::now()->addDays(4);
            $peminjaman = Peminjaman::create([
                'id_siswa' => $req->id_siswa,
                'tanggal_pinjam' => Carbon::now()->format('Y-m-d H:i:s'),
                'tanggal_kembali' => $tenggat
            ]);

            // Simpan detail peminjaman
            foreach ($req->buku as $buku) {
                DetailPeminjaman::create([
                    'id_peminjaman_buku' => $peminjaman->id,
                    'id_buku' => $buku['id_buku'],
                    'qty' => $buku['qty']
                ]);
            }

            DB::commit(); // Simpan transaksi jika semua berhasil

            return response()->json([
                'status' => true,
                'message' => 'Peminjaman berhasil dibuat!',
                'data' => $peminjaman->load('detailPeminjaman') // Load detail peminjaman
            ]);

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan transaksi jika ada error
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan peminjaman.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getpeminjaman()
    {
        $peminjaman = Peminjaman::with('detailPeminjaman', 'siswa')->get();
        return response()->json(['status' => true, 'data' => $peminjaman]);
    }

    public function getpeminjamanid($id)
    {
        $peminjaman = Peminjaman::with('detailPeminjaman')->find($id);

        if ($peminjaman) {
            return response()->json(['status' => true, 'data' => $peminjaman]);
        } else {
            return response()->json(['status' => false, 'message' => 'Peminjaman tidak ditemukan']);
        }
    }
}


