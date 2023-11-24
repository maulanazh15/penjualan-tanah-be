<?php

use App\Models\City;
use App\Models\Distric;
use App\Models\Province;
use App\Models\Subdistric;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignIdFor(Province::class, 'prov_id')->nullable();
            $table->foreignIdFor(City::class, 'city_id')->nullable();
            $table->foreignIdFor(Distric::class, 'dis_id')->nullable();
            $table->foreignIdFor(Subdistric::class, 'subdis_id')->nullable();
            $table->double('user_latitude', 10, 6)->nullable(); // Change the precision and scale as needed
            $table->double('user_longitude', 10, 6)->nullable(); // Change the precision and scale as needed
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
