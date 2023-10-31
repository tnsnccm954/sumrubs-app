<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->unsignedFloat('google_rating', 3, 2)->default(null)->nullable()->index();
            $table->unsignedFloat('users_rating', 3, 2)->default(null)->nullable()->index();
            $table->string('place_id')->index();
            $table->string('name');
            $table->string('vicinity')->nullable();
            $table->string('formatted_address')->nullable();
            $table->string('url')->nullable();
            $table->json('photo_references')->nullable();
            $table->json('opening_hours')->nullable();
            $table->json('periods')->nullable();
            $table->json('weekday_text')->nullable();
            $table->json('location')->nullable();
            $table->bigInteger('business_status_id')->unsigned()->nullable();
            $table->foreign('business_status_id')->references('id')->on('lookup_bussiness_statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
};
