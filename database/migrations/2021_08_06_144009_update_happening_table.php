<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHappeningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('happenings', function (Blueprint $table) {
            $table->unsignedBigInteger('survey_id')->nullable();
            $table->foreign('survey_id')
                ->references('id')
                ->on('surveys')
                ->onDelete('cascade');
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
            $table->dropForeign('happenings_survey_id_foreign');
            $table->dropColumn('survey_id');
        });
    }
}
