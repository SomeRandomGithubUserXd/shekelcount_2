<?php

namespace App\Http\Requests\User\Entry;

use App\Models\Mutator;
use App\Rules\RecordsBelongToUserRule;
use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'schema_id' => ['required', 'string', 'exists:entry_import_banks,id'],
            'file' => ['required', 'file'],
            'mutator_ids' => ['nullable', 'array', new RecordsBelongToUserRule(
                auth()->id(),
                'user_id',
                new Mutator()
            )]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
