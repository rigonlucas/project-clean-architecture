<?php

use Core\Domain\Enum\Project\StatusProjectEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name', 255);
            $table->string('description', 500);
            $table->foreignUuid('created_by_user_uuid')
                ->constrained('users', 'uuid')
                ->onDelete('cascade');
            $table->foreignUuid('account_uuid')
                ->constrained('accounts', 'uuid');
            $table->dateTime('start_at')->default(null)->nullable();
            $table->dateTime('finish_at')->default(null)->nullable();

            $status = array_map(
                fn(StatusProjectEnum $enum) => $enum->value,
                StatusProjectEnum::cases()
            );
            $table->enum(column: 'status', allowed: $status)
                ->comment(implode(' | ', $status))
                ->default(StatusProjectEnum::BACKLOG->value);

            $table->timestamps();
            $table->softDeletes();
            $table->index(['account_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
