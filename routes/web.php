<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\Entry\CategoryController;
use App\Http\Controllers\User\Entry\EntryController;
use App\Http\Controllers\User\MutatorController;
use App\Http\Controllers\User\SearchController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::group(['prefix' => 'entries', 'as' => 'entries.'], function () {
        Route::get('/', [EntryController::class, 'index'])->name('index');
        Route::post('/import', [EntryController::class, 'import'])->name('import');
        Route::post('/store', [EntryController::class, 'store'])->name('store');
        Route::post('/transfer', [EntryController::class, 'transfer'])->name('transfer');
        Route::post('/delete_many', [EntryController::class, 'deleteMany'])->name('delete_many');
        Route::patch('/update', [EntryController::class, 'update'])->name('update');
        Route::patch('/unmark_new/{entry}', [EntryController::class, 'unmarkNew'])->name('unmark_new');
        Route::delete('/delete/{entry}', [EntryController::class, 'delete'])->name('delete');
        Route::group(['prefix' => 'categories', 'as' => 'categories.'], function () {
            Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
            Route::post('/store', [CategoryController::class, 'store'])->name('store');
            Route::patch('/update', [CategoryController::class, 'update'])->name('update');
            Route::delete('/delete/{category}', [CategoryController::class, 'delete'])->name('delete');
        });
    });
    Route::group(['prefix' => 'search', 'as' => 'search.'], function () {
        Route::get('/', [SearchController::class, 'index'])->name('index');
    });
});

require __DIR__ . '/auth.php';
