<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    //make relation for created_by_user_uuid
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_uuid');
    }

    //account_id
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_uuid');
    }
}
