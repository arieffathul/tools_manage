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
        Schema::create('borrow_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrow_id')->nullable()->constrained('borrows')->onDelete('cascade');
            $table->foreignId('returner_id')->constrained('engineers')->onDelete('cascade');
            $table->string('job_reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_returns');
    }
};
