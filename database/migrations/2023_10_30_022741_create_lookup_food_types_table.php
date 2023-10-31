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
        Schema::create('lookup_food_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_food_type_id')->nullable();
            $table->string('category_type');
            $table->string('name');
            $table->string('display_name');
            $table->boolean('is_modifiable');
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
        Schema::dropIfExists('lookup_food_types');
    }
};
