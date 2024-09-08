<?php

namespace App\Models;

use App\Support\Models\HasCreatedByUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTask extends Model
{
    use HasFactory;
    use HasCreatedByUser;
    use SoftDeletes;

    protected $table = 'project_tasks';

    protected $fillable = [
        'project_uuid',
        'task_uuid',
        'deleted_at',
        'ulid_deletion',
    ];
}
