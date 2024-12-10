<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Siswa extends Model
{
use HasFactory;
public $timestamps= null;
protected $table="siswa";
protected $primarykey="id";
protected $fillable=['nama_siswa','alamat','tanggal_lahir','gender','no_tlp','id_kelas'];
}