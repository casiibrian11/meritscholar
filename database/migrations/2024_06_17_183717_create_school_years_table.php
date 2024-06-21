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
        Schema::create('school_years', function (Blueprint $table) {
            $table->id();
            $table->integer('start_year');
            $table->integer('end_year');
            $table->enum('semester', ['1st','2nd','3rd','Summer', NULL])->default(NULL);
            $table->unsignedBigInteger('user_id');
            $table->boolean('visible')->default(true);
            $table->boolean('active')->default(true);
            $table->softDeletes();

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
        Schema::dropIfExists('school_years');
    }
};
