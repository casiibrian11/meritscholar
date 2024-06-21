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
        Schema::create('scholarship_offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sy_id');
            $table->unsignedBigInteger('scholarship_id');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('scholarship_id')->references('id')->on('scholarships')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('scholarship_offers');
    }
};
