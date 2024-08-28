<?php

namespace App\Http\Requests\V1\Project;

use Core\Domain\Enum\Project\StatusProjectEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:500'],
            'status' => ['required', new Enum(StatusProjectEnum::class)],
            'start_at' => ['nullable', 'date:d/m/Y'],
            'finish_at' => ['nullable', 'dated/m/Y'],
        ];
    }
}
