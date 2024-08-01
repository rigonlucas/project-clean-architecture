<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'account_users');
    }

    public function usersWithPivotData(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'account_users')
            ->with('accounts')
            ->withTimestamps()
            ->withPivot('uuid_registration', 'deleted_at');
    }
}
