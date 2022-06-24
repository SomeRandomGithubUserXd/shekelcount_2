<?php

namespace App\Models;

use App\Enums\TimezoneConversionOption;
use App\Models\Service\EntryImportBank;
use App\Traits\InteractsWithTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entry extends Model
{
    use InteractsWithTime;

    protected $fillable = ['category_id', 'performed_at', 'sum', 'description', 'entry_import_bank_id', 'was_recently_imported'];

    protected $appends = ['is_new'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getPerformedAtAttribute(string $performedAt): string
    {
        return $this->strToTimezone($performedAt, TimezoneConversionOption::UTCToMoscow);
    }

    public function entryImportBank(): BelongsTo
    {
        return $this->belongsTo(EntryImportBank::class);
    }

    public function getIsNewAttribute(): bool
    {
        $isNew = false;
        if ($this->was_recently_imported &&
            $this->updated_at->equalTo($this->created_at)) {
            $isNew = true;
        }
        return $isNew;
    }

    protected function sum(): Attribute
    {
        return new Attribute(
            get: fn($sum) => floatval($sum) / 100,
            set: fn($sum) => floatval($sum) * 100
        );
    }
}
