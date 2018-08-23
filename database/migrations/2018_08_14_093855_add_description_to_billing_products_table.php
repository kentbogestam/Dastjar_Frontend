<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionToBillingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('billing_products', function (Blueprint $table) {
            $table->string('product_id')->nullable()->after('id');
            $table->string('plan_id')->nullable()->after('product_name');
            $table->string('usage_type')->nullable()->after('price');            
            $table->text('description')->nullable()->after('usage_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('billing_products', function($table)
        {
            $table->dropColumn('product_id');
            $table->dropColumn('plan_id');
            $table->dropColumn('usage_type');
            $table->dropColumn('description');
        });
    }
}
