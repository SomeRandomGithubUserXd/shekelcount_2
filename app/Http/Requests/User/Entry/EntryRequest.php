<?php

namespace App\Http\Requests\User\Entry;

use App\Models\Category;
use App\Rules\RecordBelongsToUserRule;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class EntryRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'performed_at' => Carbon::parse($this->performed_at)->toDateTimeString(),
        ]);
    }

    public function rules(): array
    {
        return [
            "category_id" => ['required', new RecordBelongsToUserRule(
                auth()->id(),
                'user_id',
                new Category()
            )],
            "sum" => ['required', 'numeric', 'min:0.01'],
            "description" => ['required', 'string'],
            "performed_at" => ['required', 'date']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
