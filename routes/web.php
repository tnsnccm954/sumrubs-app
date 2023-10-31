<?php

use App\Http\Controllers\API\GoogleMapApiController;
use App\Http\Controllers\ProfileController;
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
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    // ]);
    return Inertia::render('Index', [
        'bindingPattern' => '/*\[v-binding\]*/',
        'nearbySearchUrl' => route('place.nearby'),
        'getPlaceDetailUrl' => route('place.detail', ['place' => '[v-binding]']),
        'getPlacePhotoUrl' => route('place.photo', ['photoReference' => '[v-binding]']),


    ]);
});

Route::get('/placesearch', function () {
    return Inertia::render('PlaceSearch', []);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
