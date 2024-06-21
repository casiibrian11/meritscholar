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
        Schema::create('application_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sy_id');
            $table->unsignedBigInteger('course_id');
            $table->string('year_level')->nullable();
            $table->string('section')->nullable();
            $table->string('units_enrolled')->nullable();
            $table->string('gwa')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('application_details');
    }
};
