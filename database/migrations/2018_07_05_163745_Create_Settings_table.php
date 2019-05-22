<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->text('logo_path')->nullable();
			$table->text('display_name');
			$table->text('display_system');
			$table->text('mail_driver')->nullable();
			$table->text('mail_host')->nullable();
			$table->text('mail_port')->nullable();
			$table->text('mail_username')->nullable();
			$table->string('mail_password')->nullable();
			$table->text('mail_encryption')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
