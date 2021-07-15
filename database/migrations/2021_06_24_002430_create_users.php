<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 20)->nullable();
            $table->string('lastname', 20)->nullable();
            $table->string('email', 100);
            $table->string('password', 100);
            $table->softDeletes('deleted_at');
            $table->timestamps();

            $table->index(['firstname', 'lastname'], 'name_idx');
            $table->index('email', 'email_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
