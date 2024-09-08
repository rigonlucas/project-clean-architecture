<?php

namespace App\Models;

use App\Support\Models\HasCreatedByUser;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlowStep extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUuids;
    use HasCreatedByUser;

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    protected $table = 'flow_steps';

    protected $fillable = [
        'uuid',
        'flow_uuid',
        'name',
        'description',
        'integration',
    ];

    public function flow(): BelongsTo
    {
        return $this->belongsTo(Flow::class, 'flow_uuid', 'uuid');
    }

    public function nextStep(): BelongsTo
    {
        return $this->belongsTo(FlowStep::class, 'flow_step_uuid', 'uuid');
    }

    public function integration(): BelongsTo
    {
        return $this->belongsTo(FlowStepIntegration::class, '', 'uuid');
    }
}
