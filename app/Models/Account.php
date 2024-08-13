<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property mixed $id
 * @property mixed|string $name
 * @property mixed|string $uuid
 */
class Account extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'uuid',
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
