<?php

namespace App\Http\Controllers\User;

use App\Enums\TimezoneConversionOption;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SearchRequest;
use App\Traits\InteractsWithTime;
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
            ->with(['entryImportBank', 'category'])
            ->orderByDesc('performed_at');
        $miscQuery = clone $entriesQuery;
        return inertia('User/Search', [
            'entries' => $entriesQuery->paginate(12),
            'categories' => auth()->user()->categories,
            'misc' => [
                'entries_total' => $miscQuery->sum('sum') / 100
            ]
        ]);
    }
}
