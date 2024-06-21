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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scholarship_offer_id');
            $table->unsignedBigInteger('sy_id');
            $table->boolean('completed')->nullable();
            $table->boolean('approved')->nullable();
            $table->boolean('under_review')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('scholarship_offer_id')->references('id')->on('scholarship_offers')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('sy_id')->references('id')->on('school_years')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
};
