<?php

namespace App\Models;

use App\Enums\TimezoneConversionOption;
use App\Traits\InteractsWithTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use InteractsWithTime;

    protected $fillable = ['user_id', 'name', 'color', 'icon'];

    public function getCreatedAtAttribute(string $createdAt): string
    {
        return $this->strToTimezone($createdAt, TimezoneConversionOption::UTCToMoscow);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }
}
