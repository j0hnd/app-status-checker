<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLoginAsTokenUrlOnEndpointDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('endpoint_details', function (Blueprint $table) {
            $table->string('login_as_token_url', 155)->nullable()->after('token_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('endpoint_details', function (Blueprint $table) {
            $table->dropColumn('login_as_token_url');
        });
    }
}
