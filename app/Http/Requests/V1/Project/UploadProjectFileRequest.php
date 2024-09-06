<?php

namespace App\Http\Requests\V1\Project;

use Illuminate\Foundation\Http\FormRequest;

class UploadProjectFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_uuid' => ['required', 'uuid'],
            'file' => ['required', 'file'],
        ];
    }
}
