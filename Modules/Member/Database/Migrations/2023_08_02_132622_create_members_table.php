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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('member_code', 128)->index()->unique()->comment('Unique code of member');
            $table->string('member_name', 256)->index()->comment('Name of member');
            $table->string('member_email', 256)->index()->comment('Email of member');
            $table->string('member_password')->comment('Password of member');
            $table->string('member_phone_number', 45)->comment('Phone number of member');
            $table->dateTime('member_birth_date')->comment('Birth date of member');
            $table->text('member_avatar')->comment('Avatar of member');
            $table->integer('member_creator_id')->nullable()->comment('ID creator of member');
            $table->integer('member_updater_id')->nullable()->comment('ID updater of member');
            $table->tinyInteger('member_is_import_from_system')->default(1)->comment('Created member classification flag 1: Created by system 0: Created by importing batch file');
            $table->tinyInteger('member_reset_password_flg')->comment('0: reset 1: unreset');
            $table->dateTime('member_forget_password_time')->comment('Time forgot password of member');
            $table->tinyInteger('member_is_lock_flg')->comment('0: lock 1: unlock');
            $table->timestamp('email_verified_at')->nullable();
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('members');
    }
};
