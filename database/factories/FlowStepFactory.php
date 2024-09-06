<?php

namespace Database\Factories;

use App\Models\Flow;
use App\Models\FlowStep;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<FlowStep>
 */
class FlowStepFactory extends Factory
{
    protected $model = FlowStep::class;

    public function definition()
    {
        return [
            'uuid' => Str::uuid(),
            'flow_uuid' => Flow::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'integration' => $this->faker->word,
        ];
    }
}
