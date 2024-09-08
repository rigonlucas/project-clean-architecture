<?php

namespace App\Models;

use App\Support\Models\HasCreatedByUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectCard extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;
    use HasCreatedByUser;

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'description',
    ];

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class, 'project_tasks', 'project_uuid', 'task_uuid');
    }
}
