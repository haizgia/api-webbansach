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
        Schema::create('billdetails', function (Blueprint $table) {
            $table->integer('bill_id')->unsigned();
            $table->integer('book_id')->unsigned();
            $table->integer('price');
            $table->integer('quanty');
            $table->integer('total');
            $table->timestamps();

            $table->foreign('bill_id')->references('bill_id')->on('bills');
            $table->foreign('book_id')->references('book_id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('billdetails');
    }
};
