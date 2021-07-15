<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->text('description')->nullable();
            $table->string('application_url', 255);
            $table->string('application_type', 20);
            $table->integer('added_by')->unsigned();
            $table->softDeletes('deleted_at');
            $table->timestamps();

            $table->index('name', 'application_name_idx');
            $table->index('application_url', 'application_url_idx');
            $table->index('application_type', 'application_type_idx');
            $table->index('added_by', 'added_by_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
}
