<?php

namespace App\Http\Requests\User\Entry\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', $this->getNameUniqueRule()],
            'color' => ['required', 'min:7', 'max:7'],
            'icon' => ['required', Rule::in(config('icons.data'))]
        ];
    }

    protected function getNameUniqueRule()
    {
        return Rule::unique('categories')->where(function ($query) {
            return $query->where('user_id', auth()->id());
        });
    }

    public function authorize(): bool
    {
        return true;
    }
}
