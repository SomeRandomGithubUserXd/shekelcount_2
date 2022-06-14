<?php

namespace App\Rules;

use App\Models\Entry;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class EntryBelongsToUserRule implements Rule
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function passes($attribute, $value): bool
    {
        return Entry::whereHas('category', function (Builder $query) {
            $query->where(['user_id' => $this->userId]);
        })->where(['id' => $value])->exists();
    }

    public function message(): string
    {
        return "Entry isn't yours.";
    }
}
