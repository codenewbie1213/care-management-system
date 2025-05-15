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
        Schema::create('audit_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_template_id')->constrained('audit_templates')->onDelete('cascade');
            $table->string('question_text');
            $table->string('type'); // e.g., multiple_choice, text, date
            $table->decimal('score', 8, 2)->default(0);
            $table->json('options')->nullable(); // For multiple choice
            $table->boolean('required')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_questions');
    }
};
