<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThreeColumnsInCompanyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_details', function (Blueprint $table) {
            $table->unSignedBigInteger('currency_id')->after('country_id')->nullable();
            $table->unSignedBigInteger('timezone_id')->after('country_id')->nullable();
            $table->unSignedBigInteger('language_id')->after('country_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies');
            $table->foreign('timezone_id')->references('id')->on('timezones');
            $table->foreign('language_id')->references('id')->on('languages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_details', function (Blueprint $table) {
            //
        });
    }
}
