<?php

namespace App\Traits;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\Route;

trait HasPagination
{
    public function preventEmptyPage(LengthAwarePaginator $paginated, Route $route, array $routeModelBinding = []): bool|Redirector|RedirectResponse|Application
    {
        if ($paginated->currentPage() > $paginated->lastPage()) {
            return redirect(route(
                $route->getName(),
                $routeModelBinding + [$paginated->getPageName() => $paginated->lastPage()]
            ));
        }
        return false;
    }
}
