<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property DateTimeInterface|mixed|null $birthday
 * @property mixed|string|null $password
 * @property mixed|string $email
 * @property mixed|string $name
 * @property mixed|string $uuid
 * @property mixed|int $role
 * @method BelongsTo accounts()
 * @property int|mixed|null $account_uuid
 */
class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    use HasUuids;

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'email',
        'password',
        'birthday',
        'account_uuid',
        'role'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
