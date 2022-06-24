<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;

class EntriesBelongsToUserRule implements Rule
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function passes($attribute, $value): bool
    {
        if (!User::find($this->userId)
            ->entries()
            ->whereIn('entries.id', $value)
            ->count()) return false;
        return true;
    }

    public function message(): string
    {
        return 'One of selected entries does not belong to user.';
    }
}
