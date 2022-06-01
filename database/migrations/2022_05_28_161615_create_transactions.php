<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('users_id');
            $table->text('address')->nullable();

            $table->string('payment_method')->default('MANUAL');
            $table->string('payment_status')->default('PENDING');
            $table->string('payment_code')->nullable();

            $table->float('total_price')->default(0);

            $table->float('shipping_price')->default(0);
            $table->string('shipping_status')->default('PENDING');
            $table->string('shipping_code')->nullable();
            $table->string('shipping_type')->default('REGULER');
            $table->string('shipping_service')->default('JNE');

            $table->softDeletes();
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
        Schema::dropIfExists('transactions');
    }
}
