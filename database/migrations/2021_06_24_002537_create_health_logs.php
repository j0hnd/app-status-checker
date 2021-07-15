<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('application_id')->unsigned();
            $table->integer('http_code')->unsigned();
            $table->text('extras');
            $table->timestamps();

            $table->index('application_id', 'application_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('health_logs');
    }
}
