<?php

namespace App\Services\ImportEntries\Mutators;

use App\Services\ImportEntries\EntryEntity;

abstract class AbstractMutator
{
    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    abstract public function getMutated(EntryEntity $entity): EntryEntity;

    abstract public function getVisualisation(): MutatorVisualisation;

    public function clearAfterMutation(): void
    {
        // Polymorphic method to be overwritten
    }

    public function getAdditionalInfo(): array
    {
        return [];
    }
}
