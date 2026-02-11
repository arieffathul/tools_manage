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
        Schema::create('broken_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tool_id')->constrained('tools')->onDelete('cascade');
            $table->foreignId('reported_by')->constrained('engineers')->onDelete('cascade');
            $table->foreignId('handled_by')->nullable()->constrained('engineers')->onDelete('set null');
            $table->integer('quantity')->default(1);
            $table->string('locator')->nullable();
            $table->string('status')->default('unidentified');
            $table->string('image')->nullable();
            $table->string('issue')->nullable();
            $table->string('last_used')->nullable();
            $table->string('action')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broken_tools');
    }
};
