<?php

namespace App\Traits;


use App\Models\Entry;
use Illuminate\Database\Eloquent\Builder;

trait CRUDsEntries
{
    public function entryBelongsToUser(int $userId, int $entryId): bool
    {
        return Entry::query()
            ->whereHas('category', fn(Builder $query) => $query->where(['user_id' => $userId]))
            ->where(['id' => $entryId])->exists();
    }
}
