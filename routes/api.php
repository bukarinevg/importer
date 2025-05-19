<?php

use App\Http\Controllers\ImportController as ImportControllerAlias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/import', [ImportControllerAlias::class, 'upload']);
Route::get('/export', [ImportControllerAlias::class, 'export']);
