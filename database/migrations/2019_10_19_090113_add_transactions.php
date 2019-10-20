<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->dateTime('executed_at')->nullable();
            $table->string('description');
            $table->string('type');
            $table->double('amount');

            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('accounts');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
