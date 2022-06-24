<?php

namespace App\Services;

use App\Enums\TimezoneConversionOption;
use App\Models\Entry;
use App\Models\User;
use App\Traits\InteractsWithTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    use InteractsWithTime;

    protected int $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    public function getCharts(): array
    {
        return [
            'current_year_entries' => $this->getCurrentYearEntriesChart(),
            'categories_sum_stats' => $this->getCategoriesSumStats()
        ];
    }

    protected function entries(): HasManyThrough
    {
        return User::find($this->userId)->entries();
    }

    protected function getCurrentYearEntriesChart(): array
    {
        $entries = $this->entries()
            ->whereYear('performed_at', date('Y'))
            ->select(
                DB::raw('(SUM(entries.sum) / 100) AS entries_sum'),
                DB::raw('MONTHNAME(entries.performed_at) month')
            )
            ->groupBy('month', 'laravel_through_key')
            ->get()
            ->sortBy(fn($arr) => Carbon::parse($arr['month'])->format("m"));
        return [
            'labels' => $entries->pluck('month'),
            'data' => $entries->pluck('entries_sum')
        ];
    }

    protected function getCategoriesSumStats(): array
    {
        $entries = $this->entries()
            ->with('category')
            ->select(
                'category_id',
                DB::raw('(SUM(entries.sum) / 100) AS entries_sum'),
            )
            ->groupBy('category_id', 'laravel_through_key')
            ->get();
        return [
            'labels' => $entries->pluck('category.name'),
            'data' => $entries->pluck('entries_sum'),
            'colors' => $entries->pluck('category.color')
        ];
    }
}
