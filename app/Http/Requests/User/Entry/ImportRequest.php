<?php

namespace App\Http\Requests\User\Entry;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'schema_id' => ['required', 'string', 'exists:entry_import_banks,id'],
            'file' => [
                'required',
                'file',
            ]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
