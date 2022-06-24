<?php

namespace App\Services\ImportEntries;

use App\Models\Category;
use App\Models\Mutator;
use Carbon\Carbon;

class EntryEntity
{
    protected Category $category;

    protected Carbon $performedAt;

    protected int $sum;

    protected string $description;

    public function __construct(Category $category, Carbon $performedAt, int $sum, string $description)
    {
        $this->category = $category;
        $this->performedAt = $performedAt;
        $this->sum = $sum;
        $this->description = $description;
    }

    public function mutate(Mutator $mutator): self
    {
        $mutated = $mutator->getMutatorInstance()->getMutated($this);
        $this->category = $mutated->getCategory();
        $this->performedAt = $mutated->getPerformedAt();
        $this->sum = $mutated->getSum();
        $this->description = $mutated->getDescription();
        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getPerformedAt(): Carbon
    {
        return $this->performedAt;
    }

    public function getSum(): int
    {
        return $this->sum;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function toModelArray(): array
    {
        return [
            "category_id" => $this->category->id,
            "performed_at" => $this->performedAt,
            "sum" => $this->sum,
            "description" => $this->description,
//            "was_recently_imported" => 1,
        ];
    }
}
