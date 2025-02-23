<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Peminjaman;
use App\Models\Pengembalian;

class PengembalianController extends Controller
{
    public function kembalipeminjaman($id)
{
    $tgl_kembali = Carbon::now();
    $peminjaman = Peminjaman::find($id);

    if (!$peminjaman) {
        return response()->json(['status' => false, 'message' => 'Peminjaman tidak ditemukan']);
    }

    // Cek apakah sudah ada pengembalian untuk peminjaman ini
    $existingReturn = Pengembalian::where('id_peminjaman_buku', $peminjaman->id)->exists();

    if ($existingReturn) {
        return response()->json(['status' => false, 'message' => 'Buku ini sudah dikembalikan sebelumnya!']);
    }

    $tenggat = Carbon::parse($peminjaman->tenggat);
    $denda = 0;

    if ($tgl_kembali->greaterThan($tenggat)) {
        $daysLate = $tgl_kembali->diffInDays($tenggat);
        $denda = $daysLate * 1000;
    }

    $peminjaman->update([
        'tanggal_kembali' => $tgl_kembali
    ]);

    Pengembalian::create([
        'id_peminjaman_buku' => $peminjaman->id,
        'tanggal_pengembalian' => $tgl_kembali->format('Y-m-d H:i:s'),
        'denda' => $denda,
    ]);

    return response()->json(['status' => true, 'message' => 'Sukses Mengembalikan buku', 'denda' => $denda]);
}




    public function getpengembalian()
    {
        $pengembalian = Pengembalian::with('peminjaman.siswa')->get();

        return response()->json(['status' => true, 'data' => $pengembalian]);
    }

    // Fungsi mendapatkan detail buku berdasarkan ID
    public function getpengembalianid($id)
    {
        $pengembalian = Pengembalian::find($id);

        if ($pengembalian) {
            return response()->json(['status' => true, 'data' => $pengembalian]);
        } else {
            return response()->json(['status' => false, 'message' => 'Data tidak ditemukan']);
        }
    }
}
