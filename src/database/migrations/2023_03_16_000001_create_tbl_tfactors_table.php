<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblTfactorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_tfactors', function (Blueprint $table) {
            $table->id();
            $table->string('weight_bridge');
            $table->bigInteger('factor_id');
            $table->bigInteger('car_code');
            $table->string('car_number1', 15);
            $table->string('car_number2', 15);
            $table->string('driver', 50);
            $table->double('current_weight', 18, 3);
            $table->double('prev_weight', 18, 3);
            $table->string('current_date', 10);
            $table->string('current_time', 15);
            $table->bigInteger('current_timestamp');
            $table->string('prev_date', 10);
            $table->string('prev_time', 15);
            $table->bigInteger('prev_timestamp');
            $table->bigInteger('goods_code');
            $table->string('goods_name', 100);
            $table->double('unit_price', 18, 3);
            $table->bigInteger('buyer_code');
            $table->string('buyer_name', 20);
            $table->bigInteger('seller_code');
            $table->string('seller_name', 20);
            $table->bigInteger('user_id');
            $table->string('user_name');
            $table->string('user_family');
            $table->bigInteger('current_row');
            $table->bigInteger('prev_row');
            $table->string('factor_description1', 100);
            $table->string('factor_description2', 100);
            $table->string('factor_description3', 100);
            $table->string('factor_description4', 100);
            $table->string('factor_description5', 100);
            $table->string('factor_description6', 100);
            $table->string('factor_description7', 100);
            $table->string('factor_description8', 100);
            $table->string('factor_description9', 100);
            $table->tinyInteger('form_type');
            $table->tinyInteger('print_location');
            $table->double('tozin_cost', 18, 3);
            $table->bigInteger('p_no');
            $table->bigInteger('cost_id');
            $table->tinyInteger('factor_mode');
            $table->string('degree', 16);
            $table->tinyInteger('tax');
            $table->bigInteger('user_edit_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tbl_tfactors');
    }
}
