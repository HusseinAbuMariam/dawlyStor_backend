<?php

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

$settingsRoutes = require base_path('routes/settings.php');

// Route::get('/', function () {
//     return Inertia::render('welcome', [
//         'canRegister' => Features::enabled(Features::registration()),
//     ]);
// })->name('home');



Route::middleware(['auth:store'])->group(function () {
    Route::get('store', [App\Http\Controllers\dashboard\store\DashboardController::class, 'index'])->name('store.dashboard');
});

Route::middleware(['auth:admin,store'])
    ->group(function () {
        Route::post('/upload-temp', [App\Http\Controllers\TempUploadController::class, 'store'])->name('upload-temp');
        Route::delete('/revert-temp', [App\Http\Controllers\TempUploadController::class, 'revert'])->name('revert-temp');
        Route::get('/load-file/{source}', [App\Http\Controllers\TempUploadController::class, 'load'])->name('load-file');
        Route::delete('/remove-file/{source}', [App\Http\Controllers\TempUploadController::class, 'remove'])->name('remove-file');
    });

Route::get('language/{locale}', function ($locale) {
    $supportedLocales = config('app.supported_locales');

    if (!in_array($locale, $supportedLocales)) {
        $locale = config('app.locale', 'en');
    }

    app()->setLocale($locale);

    return redirect()->back()->withCookie(cookie('locale', $locale, 60 * 24 * 365));
});


require __DIR__ . '/settings.php';
require __DIR__ . '/dashboard/admin.php';
require __DIR__ . '/dashboard/store.php';
