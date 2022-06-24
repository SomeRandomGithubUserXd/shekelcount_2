<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\ImportEntries\AbstractEntryParser;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $service = new DashboardService(auth()->id());
        return inertia()->render('User/Dashboard', [
            'categories' => $user
                ->categories()
                ->orderBy('name')
                ->get(),
            'charts' => $service->getCharts(),
            'import' => AbstractEntryParser::getImportMeta(),
            'mutators' => $user->mutators
        ]);
    }
}
