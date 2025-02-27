<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypalCredentialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypal_credentials', function (Blueprint $table) {
            $table->id();
            $table->string('paypal_client_id')->unique();
            $table->string('paypal_secret')->unique();
            $table->string('paypal_environment')->unique();
            $table->string('webhook_secret')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('paypal_credentials');
    }
}
