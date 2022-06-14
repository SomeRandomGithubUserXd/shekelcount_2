<?php

namespace App\Services\ImportEntries;

use Carbon\Carbon;

class EntryEntity
{
    protected int $categoryId;

    protected Carbon $performedAt;

    protected int $sum;

    protected string $description;

    public function __construct(int $categoryId, Carbon $performedAt, int $sum, string $description)
    {
        $this->categoryId = $categoryId;
        $this->performedAt = $performedAt;
        $this->sum = $sum;
        $this->description = $description;
    }

    public function toArray(): array
    {
        return [
            "category_id" => $this->categoryId,
            "performed_at" => $this->performedAt,
            "sum" => $this->sum,
            "description" => $this->description,
        ];
    }
}
