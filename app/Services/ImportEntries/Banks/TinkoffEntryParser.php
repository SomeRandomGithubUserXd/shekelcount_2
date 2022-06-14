<?php

namespace App\Services\ImportEntries\Banks;

use App\Enums\TimezoneConversionOption;
use App\Models\User;
use App\Services\ImportEntries\AbstractEntryParser;
use App\Services\ImportEntries\EntryEntity;
use App\Traits\InteractsWithTime;

class TinkoffEntryParser extends AbstractEntryParser
{
    use InteractsWithTime;

    protected function toEntryEntity(array $entry): EntryEntity
    {
        return new EntryEntity(
            User::find($this->userId)->categories()->firstOrCreate([
                'name' => $entry['Категория'] ?? "ДРУГИЕ ОПЕРАЦИИ"
            ])->id,
            $this->strToTimezone($entry['Дата операции'], TimezoneConversionOption::MoscowToUTC),
            -$entry['Сумма платежа'],
            $entry['Описание']
        );
    }

    protected function getFilteredArray(): array
    {
        $collection = collect(parent::getFilteredArray());
        $collection = $collection
            ->where('Статус', '=', 'OK')
            ->where('Сумма платежа', '<', 0);
        return $collection->toArray();
    }
}
