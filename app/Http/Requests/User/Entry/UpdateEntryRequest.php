<?php

namespace App\Http\Requests\User\Entry;

use App\Rules\EntryBelongsToUserRule;

class UpdateEntryRequest extends EntryRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                "entry_id" => ['required', new EntryBelongsToUserRule(auth()->id())]
            ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
