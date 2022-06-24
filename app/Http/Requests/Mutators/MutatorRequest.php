<?php

namespace App\Http\Requests\Mutators;

use App\Models\MutatorOption;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MutatorRequest extends FormRequest
{
    protected array $dynamicFields = [];

    public static function getStaticFields(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('mutators')],
            'mutator_option_id' => ['required', 'int', 'exists:mutator_options,id']
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->mutator_option_id) {
            $mutatorOption = MutatorOption::find($this->mutator_option_id);
            $this->dynamicFields = $mutatorOption->mutator_option_rules;
        }
    }

    public function rules(): array
    {
        return self::getStaticFields() + $this->dynamicFields;
    }

    public function authorize(): bool
    {
        return true;
    }
}
