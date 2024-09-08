<?php

namespace App\Models;

use App\Support\Models\HasCreatedByUser;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\UuidInterface;

/**
 * @property int $status
 * @property string $description
 * @property string $name
 * @property string $created_by_user_uuid
 * @property int $account_uuid
 * @property DateTimeInterface $start_at
 * @property DateTimeInterface $finish_at
 * @property string $uuid
 * @property DateTimeInterface $deleted_at
 */
class Project extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;
    use HasCreatedByUser;

    public $incrementing = false;
    /**
     * @var mixed|UuidInterface
     */
    protected $table = 'projects';
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
        'account_uuid',
        'created_by_user_uuid',
        'status',
        'start_at',
        'finish_at',
        'uuid',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_uuid');
    }

    public function files(): HasMany
    {
        return $this->hasMany(ProjectFile::class, 'project_uuid', 'uuid');
    }

    public function cards(): HasMany
    {
        return $this->hasMany(ProjectCard::class, 'project_uuid', 'uuid');
    }

    public function tasks(): HasManyThrough
    {
        return $this->hasManyThrough(
            Task::class,
            ProjectTask::class,
            'project_uuid',
            'uuid',
            'uuid',
            'task_uuid'
        );
    }
}
