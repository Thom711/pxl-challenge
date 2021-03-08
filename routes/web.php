<?php

use App\Jobs\CheckFile;
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

Route::get('/', function () {
    $jobMessage = '';

    return view('welcome', [
        'job' => $jobMessage,
    ]);
});

Route::post('/', function () {
    // If this was in an application this reeeeally should be in a controller.
    $jobMessage = '';

    if(request('file')) {
        $file = request('file');

        $extension = $file->guessClientExtension();

        $file = $file->storeAs('files', $file->getClientOriginalName());

        $path = 'storage/app/' . $file;

        $minAge = 18; // These two could be inputs as well.
        $maxAge = 65;

        MigrateData::dispatch($path, $extension, $minAge, $maxAge);

        $jobMessage = 'The job is now in the Queue. Run the queue:work command to execute it.';

        return view('welcome', [
            'job' => $jobMessage,
        ]);
    }
});

//  In case the front does not work: 
//
// Route::get('/', function () {
//     $path = 'resources/opdracht/challenge.json';
//     $extension = 'json';
//     $minAge = 18;
//     $maxAge = 65;

//     MigrateData::dispatch($path, $extension, $minAge, $maxAge);

//     return view('welcome', [
//         'job' => ''
//     ]);
// });
