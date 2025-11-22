<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('student_interests')) {
            Schema::create('student_interests', function (Blueprint $table) {
                $table->id('StudentInterestID');
                $table->string('MSSV', 20);
                $table->unsignedBigInteger('InterestID');
                $table->integer('InterestLevel')->comment('1-5 scale');
                $table->timestamp('UpdatedAt')->useCurrent()->useCurrentOnUpdate();
                
                $table->foreign('MSSV')->references('MSSV')->on('sinhvien')->onDelete('cascade');
                $table->foreign('InterestID')->references('InterestID')->on('interests')->onDelete('cascade');
                $table->unique(['MSSV', 'InterestID']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_interests');
    }
};
