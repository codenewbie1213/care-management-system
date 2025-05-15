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
        Schema::create('action_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->nullable()->constrained('audits')->onDelete('set null');
            $table->string('action_item');
            $table->text('description')->nullable();
            $table->enum('status', ['open', 'in_progress', 'completed', 'overdue'])->default('open');
            $table->date('due_date');
            $table->foreignId('responsible_user_id')->constrained('users');
            $table->text('progress')->nullable();
            $table->string('completion_evidence')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_plans');
    }
};
