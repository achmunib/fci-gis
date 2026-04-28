<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outlets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->string('id_pemda')->nullable();
            $table->string('kode_barang')->nullable();
            $table->string('register')->nullable();
            $table->decimal('luas', 10, 2)->nullable();
            $table->year('tahun_pengadaan')->nullable();
            $table->string('penggunaan')->nullable();
            $table->decimal('harga', 15, 2)->nullable();
            $table->string('address')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('nomor_sertifikat')->nullable();
            $table->date('tanggal_sertifikat')->nullable();
            $table->string('hak')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_photo')->nullable();
            $table->string('latitude', 15)->nullable();
            $table->string('longitude', 15)->nullable();
            $table->text('polygon')->nullable();
            $table->integer('id_opd')->nullable();
            $table->string('nama_opd')->nullable();
            $table->unsignedInteger('creator_id');
            $table->timestamps();

            $table->foreign('creator_id')->references('id')->on('users')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outlets');
    }
}
