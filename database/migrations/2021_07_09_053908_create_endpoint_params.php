<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndpointParams extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endpoint_params', function (Blueprint $table) {
            $table->integer('application_id')->unsigned();
            $table->string('key', 255);
            $table->string('value', 255);
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
        Schema::dropIfExists('endpoint_params');
    }
}
