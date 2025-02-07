<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;
    public $timestamps= false;
    protected $table="peminjaman_buku";
    protected $primaryKey="id";
    protected $fillable=['id_siswa','tanggal_pinjam','tanggal_kembali'];
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'id_peminjaman_buku', 'id');
    }
}
