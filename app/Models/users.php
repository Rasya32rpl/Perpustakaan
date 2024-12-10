<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class users extends Model
{
    use HasFactory;
    public $timestamps= null;
    protected $table="users";
    protected $primarykey="Id";
    protected $fillable=['Id_buku','Nama','Alamat','No_tlp','Role'];
}
