<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_history_password', function (Blueprint $table) {
            $table->id();
            $table->string('member_code', 128)->index()->nullable(false)->comment('Unique code of member');
            $table->string('member_history_password', 128)->nullable()->comment('History password of member');
            $table->dateTime('member_history_password_update')->nullable()->comment('Date time history of member');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('member_history_password');
    }
};
