<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('postalcode', function (Blueprint $table) {
            $table->unsignedInteger('postal_id')->primary();
            $table->integer('subdis_id');
            $table->integer('dis_id');
            $table->integer('city_id');
            $table->integer('prov_id');
            $table->integer('postal_code');

        $table->foreign('subdis_id')
            ->references('subdis_id')
            ->on('subdistricts')
            ;

        $table->foreign('dis_id')
            ->references('dis_id')
            ->on('districts')
            ;

        $table->foreign('city_id')
            ->references('city_id')
            ->on('cities')
            ;

        $table->foreign('prov_id')
            ->references('prov_id')
            ->on('provinces')
            ;
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postalcode');
    }
};
