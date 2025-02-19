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

        $tenggat = Carbon::parse($peminjaman->tenggat);
        $denda = 0;

        // Hitung denda jika tanggal pengembalian melebihi tenggat waktu
        if ($tgl_kembali->greaterThan($tenggat)) {
            $daysLate = $tgl_kembali->diffInDays($tenggat);
            $denda = $daysLate * 1000; // Misalnya 1000 per hari keterlambatan
        }

        // Perbarui status dan tanggal kembali di tabel peminjaman
        $peminjaman->update([
            // 'status' => 'Kembali',
            'tanggal_kembali' => $tgl_kembali
        ]);

        // Tambahkan catatan pengembalian ke tabel pengembalian
        $pengembalian = Pengembalian::create([
            'id_peminjaman_buku' => $peminjaman->id,
            'tanggal_pengembalian' => $tgl_kembali->format('Y-m-d H:i:s'), // Pastikan formatnya sesuai dengan tipe data di database
            // 'status' => 'Kembali',
            'denda' => $denda,
        ]);

        if ($pengembalian) {
            return response()->json(['status' => true, 'message' => 'Sukses Mengembalikan buku', 'denda' => $denda]);
        } else {
            return response()->json(['status' => false, 'message' => 'Gagal Menambahkan Data Pengembalian']);
        }
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
