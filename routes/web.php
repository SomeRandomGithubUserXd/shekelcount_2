<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\Entry\CategoryController;
use App\Http\Controllers\User\Entry\EntryController;
use App\Http\Controllers\User\MutatorController;
use App\Http\Controllers\User\SearchController;
use Illuminate\Support\Facades\Route;

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
    Route::resource('entries', EntryController::class);
    Route::group(['prefix' => 'entries', 'as' => 'entries.'], function () {
        Route::post('/import', [EntryController::class, 'import'])->name('import');
        Route::post('/transfer', [EntryController::class, 'transfer'])->name('transfer');
        Route::post('/delete_many', [EntryController::class, 'deleteMany'])->name('delete_many');
        Route::patch('/unmark_new/{entry}', [EntryController::class, 'unmarkNew'])->name('unmark_new');
        Route::resource('categories', CategoryController::class);
        Route::post('/categories/destroy_all', [CategoryController::class, 'destroyAll'])->name('categories.destroy_all');
    });
    Route::resource('mutators', MutatorController::class);
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
});

require __DIR__ . '/auth.php';
