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
        Schema::table('reports', function (Blueprint $table) {
            $table->foreign(['creator_user'], 'reports_ibfk_2')->references(['id'])->on('users');
            $table->foreign(['time'], 'reports_ibfk_1')->references(['id'])->on('times');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign('reports_ibfk_2');
            $table->dropForeign('reports_ibfk_1');
        });
    }
};
