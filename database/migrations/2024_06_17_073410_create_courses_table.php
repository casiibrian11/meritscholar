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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code', 255);
            $table->string('course_name', 255);
            $table->unsignedBigInteger('college_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('visible')->default(true);
            $table->softDeletes();

            $table->foreign('college_id')->references('id')->on('colleges')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('courses');
    }
};
