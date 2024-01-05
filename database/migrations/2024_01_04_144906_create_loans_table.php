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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('loan_code')->unique();
            $table->unsignedBigInteger('borrower_id');
            $table->unsignedBigInteger('creator_id');
            $table->decimal('amount', 10, 2);
            $table->integer('term_months');
            $table->decimal('interest_rate', 4, 2)->default(7.9);
            $table->decimal('remaining_amount', 10, 2);

            $table->foreign('borrower_id')->references('id')->on('users');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
