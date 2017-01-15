<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     * Note: ssh_password will use base64_encode/decode
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_name')->index('site_name');
            // SSH Details
            $table->string('ssh_address', 100);
            $table->string('ssh_username', 50);
            $table->string('ssh_password', 255);
            $table->string('ssh_path', 255);
            // MySQL Details
            $table->string('db_host', 100);
            $table->string('db_database', 100);
            $table->string('db_username', 50);
            $table->string('db_password', 255);

            $table->boolean('is_db_backup_enabled')->default(0);
            $table->boolean('is_active')->default(1);
            $table->string('notes');
            $table->timestamps('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sites');
    }
}
