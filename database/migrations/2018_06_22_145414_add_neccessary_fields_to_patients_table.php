<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNeccessaryFieldsToPatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
             $table->text('gender');
			 $table->integer('room_id');
			 $table->string('adhar_number')->nullable();
			 $table->string('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn('gender');
			$table->dropColumn('room_id');
			$table->dropColumn('adhar_number');
			$table->dropColumn('remarks');
        });
    }
}
