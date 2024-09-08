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
        Schema::create('flow_steps', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('flow_uuid')
                ->constrained('flows', 'uuid')
                ->onDelete('cascade');
            $table->foreignUuid('next_step_uuid')
                ->nullable()
                ->constrained('flow_steps', 'uuid')
                ->onDelete('cascade');
            $table->foreignUuid('created_by_user_uuid')
                ->constrained('users', 'uuid')
                ->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flow_steps');
    }
};
