<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHappeningFieldsType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('happenings', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->text('place')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('happenings', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
            $table->string('place')->nullable()->change();
        });
    }
}
