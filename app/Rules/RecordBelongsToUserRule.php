<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class RecordBelongsToUserRule implements Rule
{
    protected int $userId;

    protected string $foreignKey;

    protected Model $model;

    public function __construct(int $userId, string $foreignKey, Model $model)
    {
        $this->userId = $userId;
        $this->foreignKey = $foreignKey;
        $this->model = $model;
    }

    public function passes($attribute, $value): bool
    {
        $belongsToUser = false;
        if ($this->model->newModelQuery()->where([
            'id' => $value,
            $this->foreignKey => $this->userId
        ])->exists()) {
            $belongsToUser = true;
        }
        return $belongsToUser;
    }

    public function message(): string
    {
        return "Record isn't yours";
    }
}
