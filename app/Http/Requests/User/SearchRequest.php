<?php

namespace App\Http\Requests\User;

use App\Models\Category;
use App\Rules\RecordBelongsToUserRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SearchRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "order_by.*" => ['nullable', 'string'],
            "category_id" => ['nullable', 'int', new RecordBelongsToUserRule(
                auth()->id(),
                'user_id',
                new Category()
            )],
            "sum_is_more_than" => ['nullable', 'numeric', 'min:0.01'],
            "sum_is_less_than" => ['nullable', 'numeric', 'min:0.01'],
            "description" => ['nullable', 'string'],
            "date_range.*" => ['nullable', 'date'],
            "group_by" => ['nullable', 'string']
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
