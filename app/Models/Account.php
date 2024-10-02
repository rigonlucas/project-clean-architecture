<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed|string $name
 * @property mixed|string $uuid
 * @property mixed $owner_user_id
 */
class Account extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'uuid',
        'owner_user_id',
    ];

    public function joinCodes(): HasMany
    {
        return $this->hasMany(AccountJoinCode::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
