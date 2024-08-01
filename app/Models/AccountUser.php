<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountUser extends Pivot
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'account_users';

    protected $fillable = [
        'user_id',
        'account_id',
        'uuid',
    ];

    protected $dates = [
        'deleted_at',
    ];
}
