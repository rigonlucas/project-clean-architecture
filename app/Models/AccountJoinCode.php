<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $code
 * @property int account_id
 * @property int user_id
 * @property DateTime expired_at
 */
class AccountJoinCode extends Model
{
    use HasFactory;

    protected $table = 'account_join_codes';

    protected $fillable = [
        'code',
        'account_id',
        'user_id',
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
