<?php

use App\Http\Controllers\MigrateController;
use App\Jobs\MigrateData;
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

Route::get('/', [MigrateController::class, 'store']);

// Route::get('/', function () {
//     $path = 'resources/opdracht/challenge.json';
//     $minAge = 18;
//     $maxAge = 65;

//     MigrateData::dispatch($path, $minAge, $maxAge);

//     return view('welcome');
// });
