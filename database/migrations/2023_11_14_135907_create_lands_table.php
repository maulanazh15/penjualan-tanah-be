<?php

use App\Models\City;
use App\Models\Distric;
use App\Models\Province;
use App\Models\Subdistric;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->integer('harga');
            $table->double('luas');
            $table->string('alamat');
            $table->string('foto_tanah');
            $table->foreignIdFor(Province::class, 'prov_id');
            $table->foreignIdFor(City::class, 'city_id');
            $table->foreignIdFor(Distric::class, 'dis_id');
            $table->foreignIdFor(Subdistric::class, 'subdis_id');
            $table->text('keterangan')->nullable();
            $table->double('tanah_latitude', 10, 6)->nullable(); // Change the precision and scale as needed
            $table->double('tanah_longitude', 10, 6)->nullable(); // Change the precision and scale as needed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lands');
    }
};
