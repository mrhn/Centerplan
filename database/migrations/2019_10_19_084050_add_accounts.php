<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccounts extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('account_user', function (Blueprint $table) {
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('account_user');
        Schema::dropIfExists('accounts');
    }
}
