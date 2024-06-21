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
        Schema::create('student_information', function (Blueprint $table) {
            $table->id();
            $table->string('learner_reference_number')->nullable();
            $table->string('atm_account_number')->nullable();
            $table->string('student_id')->nullable();

            // $table->unsignedBigInteger('college_id');
            // $table->unsignedBigInteger('course_id');
            // $table->string('year_level')->nullable();
            // $table->string('section')->nullable();
            // $table->string('units_enrolled')->nullable();
            // $table->string('gwa')->nullable();
            // $table->string('message')->nullable();

            $table->string('sex')->nullable();
            $table->date('birthdate')->nullable();

            $table->string('address_line')->nullable();
            $table->string('barangay')->nullable();
            $table->string('municipality')->nullable();
            $table->string('province')->nullable();
            $table->string('region')->nullable();
            
            $table->string('contact_number')->nullable();
            $table->string('monthly_income')->nullable();
            $table->string('parent_status')->nullable();
            $table->string('disability')->nullable();
            $table->string('indigenous_group')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('operations')->nullable();
            $table->boolean('step1')->default(false);
            $table->boolean('step2')->default(false);
            $table->boolean('completed')->default(false);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

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
        Schema::dropIfExists('student_information');
    }
};
