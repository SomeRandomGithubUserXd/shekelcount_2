<?php

namespace App\Http\Requests\User\Entry\Category;

use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends CategoryRequest
{
    public function rules(): array
    {
        return parent::rules() + [
                "category_id" => ['required', Rule::exists('categories', 'id')->where(function ($query) {
                    return $query->where(['user_id' => auth()->id()]);
                })]
            ];
    }

    protected function getNameUniqueRule()
    {
        $rule = parent::getNameUniqueRule();
        $rule->ignore($this->category_id);
        return $rule;
    }

    public function authorize(): bool
    {
        return true;
    }
}
