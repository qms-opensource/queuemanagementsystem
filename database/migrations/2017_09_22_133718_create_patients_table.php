<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('department_id');
            $table->integer('doctor_id')->nullable();
            $table->integer('hall_id');
            $table->integer('age');
            $table->text('address')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('crno');
            $table->integer('token');
            $table->integer('queue_status')->default(0);
            $table->longText('device_id')->nullable();
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
        Schema::dropIfExists('patients');
    }
}
