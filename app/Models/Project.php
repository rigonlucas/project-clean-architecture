<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $status
 * @property string $description
 * @property string $name
 * @property int $created_by_user_id
 * @property int $account_id
 * @property DateTimeInterface $start_at
 * @property DateTimeInterface $finish_at
 * @property string $uuid
 * @property DateTimeInterface $deleted_at
 */
class Project extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'projects';

    protected $fillable = [
        'name',
        'description',
        'account_id',
        'created_by_user_id',
        'status',
        'start_at',
        'finish_at',
        'uuid',
    ];

    //make relation for created_by_user_id
    public function createdByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    //account_id
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
