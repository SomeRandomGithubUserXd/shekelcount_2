<?php

namespace App\Services\ImportEntries\Mutators\Types;

use App\Models\Category;
use App\Models\User;
use App\Services\ImportEntries\EntryEntity;
use App\Services\ImportEntries\Mutators\AbstractMutator;
use App\Services\ImportEntries\Mutators\MutatorVisualisation;

class CategorySwitcher extends AbstractMutator
{
    protected string $targetCategoryName;

    protected string $incomingCategoryName;

    protected bool $doNotImportIncomingCategory;

    public function __construct(
        int             $userId,
        string          $targetCategoryName,
        string          $incomingCategoryName,
        bool|int|string $doNotImportIncomingCategory = null
    )
    {
        parent::__construct($userId);
        $this->targetCategoryName = $targetCategoryName;
        $this->incomingCategoryName = $incomingCategoryName;
        $this->doNotImportIncomingCategory = !!$doNotImportIncomingCategory;
    }

    public function getVisualisation(): MutatorVisualisation
    {
        return new MutatorVisualisation($this->incomingCategoryName, $this->targetCategoryName);
    }

    public function getAdditionalInfo(): array
    {
        return [
            [
                'key' => 'Do Not Import Incoming Category',
                'value' => $this->doNotImportIncomingCategory ? 'On' : 'Off'
            ]
        ];
    }

    public function getMutated(EntryEntity $entity): EntryEntity
    {
        $category = $entity->getCategory();
        if ($entity->getCategory()->name === $this->incomingCategoryName) {
            $category = Category::firstOrCreate([
                'name' => $this->targetCategoryName,
                'user_id' => $this->userId
            ]);
        }
        return new EntryEntity(
            $category,
            $entity->getPerformedAt(),
            $entity->getSum(),
            $entity->getDescription()
        );
    }

    public function clearAfterMutation(): void
    {
        if ($this->doNotImportIncomingCategory) {
            User::query()
                ->find($this->userId)
                ->categories()
                ->where(['name' => $this->incomingCategoryName])
                ->delete();
        }
    }
}
