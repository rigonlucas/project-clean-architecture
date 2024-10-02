<?php

use Core\Domain\Enum\File\FileStatusEnum;
use Core\Domain\Enum\File\FileTypeEnum;
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
            $table->foreignUuid('project_uuid')
                ->constrained('projects', 'uuid')
                ->onDelete('cascade');
            $table->foreignId('created_by_user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignUuid('account_uuid')
                ->constrained('accounts', 'uuid')
                ->onDelete('cascade');
            $table->string('file_name', 255);
            $table->string('file_path', 255);
            $table->string('file_extension', 10);
            $table->unsignedBigInteger('file_size');

            $types = array_map(
                fn(FileTypeEnum $enum) => $enum->value,
                FileTypeEnum::cases()
            );
            $table->enum(column: 'file_type', allowed: $types)
                ->comment(implode(' | ', $types))
                ->default(null)
                ->nullable();

            $status = array_map(
                fn(FileStatusEnum $enum) => $enum->value,
                FileStatusEnum::cases()
            );
            $table->enum(column: 'status', allowed: $status)
                ->comment(implode(' | ', $status))
                ->default(FileStatusEnum::AVAILABLE->value);

            $table->unsignedBigInteger(column: 'context')
                ->index()
                ->default(null);


            $table->timestamp('deletion_date')->nullable()->default(null);
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
        Schema::dropIfExists('project_files');
    }
};
