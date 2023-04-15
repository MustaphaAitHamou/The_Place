<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', function() {
        return view('index');
    });

    Route::get('/map', function() {
        $pixels = [];

        for($x=0; $x <= 99; $x++){
            $rows = [];

            for($y=0; $y <= 99; $y++){
                $rows[] = "{$x}:{$y}";
            }

            $pixels[] = Redis::mget($rows);
        }

        return $pixels;
    });
});

require __DIR__.'/auth.php';
