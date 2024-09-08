<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Project;
use App\Models\ProjectFile;
use App\Models\User;
use Core\Domain\Enum\File\FileContextEnum;
use Core\Domain\Enum\File\FileExtensionsEnum;
use Core\Domain\Enum\File\FileStatusEnum;
use Core\Domain\Enum\File\FileTypeEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectFile>
 */
class ProjectFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_uuid' => Project::factory(),
            'created_by_user_uuid' => User::factory(),
            'account_uuid' => Account::factory(),
            'file_name' => $this->faker->word,
            'file_path' => $this->faker->filePath(),
            'file_extension' => FileExtensionsEnum::DOCX->value,
            'file_size' => $this->faker->randomNumber(),
            'file_type' => FileTypeEnum::IMAGE->value,
            'status' => FileStatusEnum::PENDING->value,
            'context' => FileContextEnum::PROJECT->value,
        ];
    }
}
