<?php

namespace App\Services\ImportEntries\Mutators\Types;

use App\Models\User;
use App\Services\ImportEntries\EntryEntity;
use App\Services\ImportEntries\Mutators\AbstractMutator;
use App\Services\ImportEntries\Mutators\MutatorVisualisation;

class CategorySkipper extends AbstractMutator
{
    protected string $categoryName;

    public function __construct(int $userId, string $categoryName)
    {
        parent::__construct($userId);
        $this->categoryName = $categoryName;
    }

    public function getMutated(EntryEntity $entity): EntryEntity
    {
        return $entity;
    }

    public function clearAfterMutation(): void
    {
        User::query()
            ->find($this->userId)
            ->categories()
            ->where(['name' => $this->categoryName])
            ->delete();
    }

    public function getVisualisation(): MutatorVisualisation
    {
        return new MutatorVisualisation($this->categoryName, 0);
    }
}
