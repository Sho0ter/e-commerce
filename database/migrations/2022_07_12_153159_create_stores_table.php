<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->bigInteger('user_id')->unsigned()->index()->nullable()->unique();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->float('vat_percentage', 8, 2)->nullable()->default(0);
            $table->float('shipping_cost', 8, 2)->nullable()->default(0);
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
        Schema::dropIfExists('stores');
    }
};
