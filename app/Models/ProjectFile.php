<?php

namespace App\Models;

use Core\Domain\Enum\File\ContextFileEnum;
use Core\Domain\Enum\File\ExtensionsEnum;
use Core\Domain\Enum\File\StatusFileEnum;
use Core\Domain\Enum\File\TypeFileEnum;
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
 * @property TypeFileEnum|mixed $file_type
 * @property BytesValueObject|mixed $file_size
 * @property ExtensionsEnum|mixed $file_extension
 * @property mixed $project_uuid
 * @property mixed $created_by_user_uuid
 * @property ContextFileEnum|mixed $context
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
