<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->string('discription');
            $table->boolean('free_plan');
            $table->decimal('stripe_monthly_plan_id', $precision = 8, $scale = 2)->nullable();
            $table->decimal('paypal_monthly_plan_id', $precision = 8, $scale = 2)->nullable();
            $table->decimal('stripe_annual_plan_id', $precision = 8, $scale = 2)->nullable();
            $table->decimal('paypal_annual_plan_id', $precision = 8, $scale = 2)->nullable();
            $table->boolean('is_private');
            $table->boolean('is_recommended');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            //
        });
    }
}
