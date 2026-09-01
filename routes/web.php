<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'application' => 'TrueFace Attendance SaaS',
        'version' => '1.0.0',
        'status' => 'Running'
    ]);
});