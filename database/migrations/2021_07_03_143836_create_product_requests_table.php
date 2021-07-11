<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_requests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description', 1000);
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('photographer_id')->nullable();
            $table->string('reference_code')->unique();
            $table->enum('status', ['pending', 'accepted', 'completed'])->default('pending');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('users')
                ->onDelete('cascade');
            $table->foreign('photographer_id')->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_requests');
    }
}
