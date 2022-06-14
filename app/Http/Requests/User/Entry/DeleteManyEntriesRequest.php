<?php

namespace App\Http\Requests\User\Entry;

use App\Rules\EntriesBelongsToUserRule;
use Illuminate\Foundation\Http\FormRequest;

class DeleteManyEntriesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'entry_ids' => ['required', new EntriesBelongsToUserRule(auth()->id())]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
