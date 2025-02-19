<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    use HasFactory;
    public $timestamps= false;
    protected $table="pengembalian_buku";
    protected $primaryKey="id";
    protected $fillable=['id_peminjaman_buku','tanggal_pengembalian','denda','status'];

    public function peminjaman()
{
    return $this->belongsTo(Peminjaman::class, 'id_peminjaman_buku');
}

}

