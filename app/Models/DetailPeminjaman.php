<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = "detail_peminjaman_buku";
    protected $primaryKey = "id";
    protected $fillable = ['id_peminjaman_buku', 'id_buku', 'qty'];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman_buku');
    }
}
