<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::get('/images/{image}', function ($image){
    $basePath = public_path() . '/uploads/images/';
    if (File::exists($basePath . 'blogImages/' . $image)) {
        return response()->file($basePath . 'blogImages/'. $image);
    }
    else if (File::exists($basePath . 'postImages/' . $image)) {
        return response()->file($basePath . 'postImages/'. $image);
    }
    else if (File::exists($basePath . 'commentImages/' . $image)) {
        return response()->file($basePath . 'commentImages/'. $image);
    } 
    else {
        return response('Not found', 404);
    }
});

require __DIR__.'/auth.php';
