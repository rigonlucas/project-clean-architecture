<?php

use Core\Domain\Enum\File\StatusFileEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\Enum\Project\ProjectFileContextEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_files', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('project_id')->constrained('projects');
            $table->string('file_name', 255);
            $table->string('file_hash', 255);
            $table->string('file_path', 255);
            $table->string('file_extension', 10);
            $table->string('file_mime_type', 255);
            $table->string('file_size', 255);

            $types = array_map(
                fn(TypeFileEnum $enum) => $enum->value,
                TypeFileEnum::cases()
            );
            $table->enum(column: 'file_type', allowed: $types)
                ->comment(implode(' | ', $types))
                ->default(null)
                ->nullable();

            $status = array_map(
                fn(StatusFileEnum $enum) => $enum->value,
                StatusFileEnum::cases()
            );
            $table->enum(column: 'status', allowed: $status)
                ->comment(implode(' | ', $status))
                ->default(StatusFileEnum::PENDING->value);

            $contexts = array_map(
                fn(ProjectFileContextEnum $enum) => $enum->value,
                ProjectFileContextEnum::cases()
            );
            $table->enum(column: 'context', allowed: $contexts)
                ->index()
                ->comment(implode(' | ', $contexts))
                ->default(null);

            $table->foreignUuid('created_by_user_uuid')->constrained('users', 'uuid');
            $table->foreignUuid('account_uuid')->constrained('accounts', 'uuid');

            $table->timestamp('deletion_date')->nullable()->default(null);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_files');
    }
};
