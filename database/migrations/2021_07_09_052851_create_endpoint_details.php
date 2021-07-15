<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEndpointDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endpoint_details', function (Blueprint $table) {
            $table->id();
            $table->integer('application_id')->unsigned();
            $table->text('method');
            $table->enum('field_type', ['header', 'param', 'body']);
            $table->text('current_token')->nullable();
            $table->string('token_url', 255)->nullable();
            $table->string('content_type', 50)->nullable();
            $table->string('authorization_type', 30)->nullable();
            $table->string('app_key', 155)->nullable();
            $table->string('app_secret', 155)->nullable();
            $table->string('username', 50)->nullable();
            $table->string('password', 50)->nullable();

            $table->softDeletes('deleted_at');
            $table->timestamps();

            $table->index('application_id', 'application_id_idx');
            $table->index('field_type', 'field_type_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('endpoint_details');
    }
}
