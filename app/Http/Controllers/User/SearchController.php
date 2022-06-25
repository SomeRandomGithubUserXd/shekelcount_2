<?php

namespace App\Http\Controllers\User;

use App\Enums\TimezoneConversionOption;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SearchRequest;
use App\Traits\InteractsWithTime;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class SearchController extends Controller
{
    use InteractsWithTime;

    public function index(SearchRequest $request)
    {
        $orderBy = $request->get('orderBy');
        $dates = $request->get('date_range');
        $entriesQuery = auth()->user()
            ->entries()
            ->with(['entryImportBank', 'category'])
            ->when($request->get('category_id'),
                fn(Builder $query) => $query->where(['category_id' => $request->get('category_id')])
            )
            ->when($request->get('sum_is_more_than'),
                fn(Builder $query) => $query->where('sum', '>', $request->get('sum_is_more_than') * 100)
            )
            ->when($request->get('sum_is_less_than'),
                fn(Builder $query) => $query->where('sum', '<', $request->get('sum_is_less_than') * 100)
            )
            ->when($request->get('description'), function (Builder $query) use ($request) {
                $string = explode(' ', $request->get('description'));
                foreach ($string as $word) {
                    $query->where('description', 'like', '%' . $word . '%');
                }
            })
            ->when($dates, function (Builder $query) use ($dates) {
                if ($dates['start'] === $dates['end']) return;
                $query
                    ->where('performed_at', '>=', $this->strToTimezone(
                        $dates['start'],
                        TimezoneConversionOption::MoscowToUTC
                    ))
                    ->where('performed_at', '<=', $this->strToTimezone(
                        $dates['end'],
                        TimezoneConversionOption::MoscowToUTC
                    ));
            })
            ->when(isset($orderBy['column']),
                fn(Builder $query) => $query->orderBy($orderBy['column'], $orderBy['direction'])
            )
            ->when(!isset($orderBy['column']),
                fn(Builder $query) => $query->orderByDesc('performed_at')
            );
        $miscQuery = clone $entriesQuery;
        $entries = $entriesQuery->paginate(12);
        if ($request->get('group_by')) {
            $grouped = $entries->groupBy(
                fn($entry) => (new Carbon($entry->performed_at))->format($request->get('group_by'))
            )->sortBy(function ($entry, $key) use ($orderBy) {
                $position = (int)date('N', strtotime($key));
                if ($orderBy['column'] = 'performed_at' && $orderBy['direction'] === 'DESC') {
                    return -$position;
                }
                return $position;
            });
            if (isset($orderBy['column'])) {
                foreach ($grouped as $key => $group) {
                    $orderBy['direction'] === 'ASC'
                        ? $grouped[$key] = $group->sortBy($orderBy['column'])->values()
                        : $grouped[$key] = $group->sortByDesc($orderBy['column'])->values();
                }
            }
            $entries = [
                'is_grouped' => true,
                'total' => $entries->total(),
                'last_page' => $entries->lastPage(),
                'current_page' => $entries->currentPage(),
                'data' => $grouped,
            ];
        }
        return inertia('User/Search', [
            'entries' => $entries,
            'categories' => auth()->user()->categories,
            'misc' => [
                'entries_total' => $miscQuery->sum('sum') / 100
            ]
        ]);
    }
}
