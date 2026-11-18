<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware('web')->prefix(config('prairie.route_prefix'))->get('/prairie/toogle-sidebar', function (Request $request) {
    if ($request->collapsed) {
        session(['prairie-sidebar-collapsed' => $request->collapsed]);
    }
})->name('prairie.toogle-sidebar');

Route::middleware('web')->prefix(config('prairie.route_prefix'))->get('/prairie/spotlight', function (Request $request) {
    return app()->make(config('prairie.components.spotlight.class'))->search($request);
})->name('prairie.spotlight');

Route::middleware(['web', 'auth'])->prefix(config('prairie.route_prefix'))->post('/prairie/upload', function (Request $request) {
    $disk = $request->disk ?? 'public';
    $folder = $request->folder ?? 'editor';

    $file = Storage::disk($disk)->put($folder, $request->file('file'), 'public');
    $url = Storage::disk($disk)->url($file);

    return ['location' => $url];
})->name('prairie.upload');
