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
        Schema::table('scholarships', function (Blueprint $table) {
            $table->integer('privilege')->nullable()->after('scholarship_category_id');
            $table->boolean('is_per_semester')->default(false)->after('privilege');
            $table->integer('sort_number')->nullable()->after('is_per_semester');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropColumn('privilege');
            $table->dropColumn('is_per_semester');
            $table->dropColumn('sort_number');
        });
    }
};
