<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGambarBukuToBukuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buku', function (Blueprint $table) {
            // Menambahkan kolom gambar_buku dengan tipe string, panjang 255 karakter, dan nullable
            $table->string('gambar_buku', 255)->nullable()->after('kategori');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn('gambar_buku');
        });
    }
}
