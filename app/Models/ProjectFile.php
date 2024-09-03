<?php

namespace App\Models;

use Core\Domain\Enum\File\AllowedExtensionsEnum;
use Core\Domain\Enum\File\StatusFileEnum;
use Core\Domain\Enum\File\TypeFileEnum;
use Core\Domain\Enum\Project\ProjectFileContextEnum;
use Core\Domain\ValueObjects\BytesValueObject;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\UuidInterface;

/**
 * @property mixed|UuidInterface $uuid
 * @property mixed|UuidInterface $account_uuid
 * @property mixed|string $name
 * @property mixed|string $path
 * @property TypeFileEnum|mixed $type
 * @property BytesValueObject|mixed $size
 * @property AllowedExtensionsEnum|mixed $extension
 * @property mixed $project_id
 * @property mixed $user_id
 * @property ProjectFileContextEnum|mixed $context
 * @property StatusFileEnum|mixed $status
 */
class ProjectFile extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;

    public $incrementing = false;
    protected $table = 'project_files';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_uuid');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_uuid');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_uuid');
    }
}
