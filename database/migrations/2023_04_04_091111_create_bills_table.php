<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('bill_id');
            $table->integer('customer_id');
            $table->longText('note');
            $table->integer('total_quanty');
            $table->double('total_price');
            $table->integer('status_id')->unsigned()->default(1);
            $table->timestamps();

            // $table->foreign('customer_id')->references('user_id')->on('customers');
            $table->foreign('status_id')->references('status_id')->on('statuses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
