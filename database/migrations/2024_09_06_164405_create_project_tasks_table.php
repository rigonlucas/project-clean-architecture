<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('project_uuid')
                ->constrained('projects', 'uuid')
                ->onDelete('cascade');
            $table->foreignUuid('task_uuid')
                ->constrained('tasks', 'uuid')
                ->onDelete('cascade');
            $table->foreignId('created_by_user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            $table->string('ulid_deletion', 26)
                ->nullable()
                ->default(null)
                ->comment('Unique local identifier for deletion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_tasks');
    }
};
