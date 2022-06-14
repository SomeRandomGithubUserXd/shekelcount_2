<?php

namespace App\Http\Requests\User\Entry;

use App\Models\Category;
use App\Rules\EntriesBelongsToUserRule;
use App\Rules\EntryBelongsToUserRule;
use App\Rules\RecordBelongsToUserRule;
use Illuminate\Foundation\Http\FormRequest;

class TransferEntriesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'entry_ids' => ['required', new EntriesBelongsToUserRule(auth()->id())],
            'category_id' => ['required', new RecordBelongsToUserRule(
                auth()->id(),
                'user_id',
                new Category(),
            )]
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
