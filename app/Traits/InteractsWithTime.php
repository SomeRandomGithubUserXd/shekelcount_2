<?php

namespace App\Traits;

use App\Enums\TimezoneConversionOption;
use Carbon\Carbon;

trait InteractsWithTime
{
    public function strToTimezone(string $date, TimezoneConversionOption $option): Carbon
    {
        switch ($option) {
            case (TimezoneConversionOption::MoscowToUTC):
                $date = Carbon::parse($date, 'Europe/Moscow');
                $date->setTimezone('UTC');
                break;
            case (TimezoneConversionOption::UTCToMoscow):
                $date = Carbon::parse($date, 'UTC');
                $date->setTimezone('Europe/Moscow');
                break;
        }
        return $date;
    }
}
