<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $uuid
 * @property string $code
 * @property int account_uuid
 * @property int user_uuid
 * @property DateTime expired_at
 */
class AccountJoinCode extends Model
{
    use HasFactory;
    use HasUuids;

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    protected $table = 'account_join_codes';

    protected $fillable = [
        'code',
        'account_uuid',
        'user_uuid',
        'expired_at'
    ];

    protected $casts = [
        'expired_at' => 'datetime'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
