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
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['text','number','image','document',NULL])->default(NULL);
            $table->string('file_type')->nullable();
            $table->string('label')->nullable();
            $table->longText('description')->nullable();
            $table->text('sample')->nullable();
            $table->boolean('required')->default(true);
            
            $table->unsignedBigInteger('user_id');
            $table->boolean('visible')->default(true);
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
        Schema::dropIfExists('requirements');
    }
};
