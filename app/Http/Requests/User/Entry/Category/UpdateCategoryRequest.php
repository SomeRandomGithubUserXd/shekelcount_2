<?php

namespace App\Http\Requests\User\Entry\Category;

class UpdateCategoryRequest extends CategoryRequest
{
    protected function getNameUniqueRule()
    {
        $rule = parent::getNameUniqueRule();
        $rule->ignore($this->category_id);
        return $rule;
    }
}
