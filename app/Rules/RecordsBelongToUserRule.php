<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class RecordsBelongToUserRule implements Rule
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
        if (!$this->model
            ->where([$this->foreignKey => $this->userId])
            ->whereIn('id', $value)
            ->count()) return false;
        return true;
    }

    public function message(): string
    {
        return 'One of records does not belong to user.';
    }
}
