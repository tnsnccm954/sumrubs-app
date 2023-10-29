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
        Schema::create('role_permission', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->timestamps();

            $table->foreign('role_id')->references('id')->on('lookup_roles');
            $table->foreign('permission_id')->references('id')->on('lookup_permissions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('role_permission', function (Blueprint $blueprint) {
            $blueprint->dropForeign(['role_id']);
            $blueprint->dropForeign(['permission_id']);
        });
        Schema::dropIfExists('role_permission');
    }
};
