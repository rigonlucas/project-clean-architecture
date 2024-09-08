<?php

namespace App\Models;

use App\Support\Models\HasCreatedByUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectTask extends Model
{
    use HasFactory;
    use HasCreatedByUser;

    protected $table = 'project_tasks';

    protected $fillable = [
        'project_uuid',
        'task_uuid',
    ];
}
