<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
