<?php

namespace App\Models;

use App\Support\Models\HasCreatedByUser;
use Core\Domain\Enum\File\FileContextEnum;
use Core\Domain\Enum\File\FileExtensionsEnum;
use Core\Domain\Enum\File\FileStatusEnum;
use Core\Domain\Enum\File\FileTypeEnum;
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
 * @property mixed|string $file_name
 * @property mixed|string $file_path
 * @property FileTypeEnum|mixed $file_type
 * @property BytesValueObject|mixed $file_size
 * @property FileExtensionsEnum|mixed $file_extension
 * @property mixed $project_uuid
 * @property mixed $created_by_user_uuid
 * @property FileContextEnum|mixed $context
 * @property FileStatusEnum|mixed $status
 */
class ProjectFile extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;
    use HasCreatedByUser;

    public $incrementing = false;
    protected $table = 'project_files';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'file_extension',
        'project_uuid',
        'created_by_user_uuid',
        'account_uuid',
        'context',
        'status',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_uuid');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_uuid');
    }
}
