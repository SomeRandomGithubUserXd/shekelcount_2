<?php

namespace App\Services\ImportEntries\Mutators;

final class MutatorVisualisation
{
    protected string|int $valueBefore;

    protected string|int $valueAfter;

    public function __construct(string|int $valueBefore, string|int $valueAfter)
    {
        $this->valueBefore = $valueBefore;
        $this->valueAfter = $valueAfter;
    }

    public function getValueAfter(): string|int
    {
        return $this->valueAfter;
    }

    public function getValueBefore(): string|int
    {
        return $this->valueBefore;
    }

    public function toArray(): array
    {
        return [
            'before' => $this->valueBefore,
            'after' => $this->valueAfter
        ];
    }
}
