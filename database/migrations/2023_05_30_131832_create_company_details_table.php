<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_details', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->string('logo')->nullable();
            $table->string('organzation_type');
            $table->string('slug')->nullable();
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->unSignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('email');
            $table->string('contact_person')->nullable();
            $table->string('phone');
            $table->string('fax')->nullable();
            $table->string('ntn')->nullable();
            $table->string('cnic')->nullable();
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
        Schema::dropIfExists('company_details');
    }
}
