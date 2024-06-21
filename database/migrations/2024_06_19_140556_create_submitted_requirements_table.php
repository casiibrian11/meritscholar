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
        Schema::create('submitted_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id');
            $table->unsignedBigInteger('requirement_id');
            $table->string('attachment');
            $table->boolean('approved')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('application_id')->references('id')->on('applications')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('requirement_id')->references('id')->on('requirements')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('submitted_requirements');
    }
};
