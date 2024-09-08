<?php

use Core\Domain\Enum\Task\StatusTaskEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $status = array_map(
                fn($enum) => $enum->value,
                StatusTaskEnum::cases()
            );

            $table->uuid()->primary();
            $table->foreignUuid('created_by_user_uuid')
                ->constrained('users', 'uuid')
                ->onDelete('cascade');
            $table->foreignUuid('account_uuid')
                ->constrained('accounts', 'uuid')
                ->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum(column: 'status', allowed: $status)
                ->comment(implode(' | ', $status))
                ->default(StatusTaskEnum::PENDING->value);
            $table->dateTime('start_at')->default(null)->nullable();
            $table->dateTime('finish_at')->default(null)->nullable();
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
        Schema::dropIfExists('tasks');
    }
};
