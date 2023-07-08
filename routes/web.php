<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\JurusanController;
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
    return redirect('/login');
    // return Inertia::render('Welcome', [
    //     'canLogin' => Route::has('login'),
    //     'canRegister' => Route::has('register'),
    //     'laravelVersion' => Application::VERSION,
    //     'phpVersion' => PHP_VERSION,
    // ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('/mahasiswa')->group(function(){
        Route::get('/data', [MahasiswaController::class, 'index'])->name('mahasiswa.data');
        Route::post('/save', [MahasiswaController::class, 'store'])->name('mahasiswa.save');
        Route::delete('/delete', [MahasiswaController::class, 'destroy'])->name('mahasiswa.delete');
    });
    
    Route::prefix('/jurusan')->group(function(){
        Route::get('/fakultas', [JurusanController::class, 'getFakultas'])->name('jurusan.fakultas');
        Route::post('/prodi', [JurusanController::class, 'getProdi'])->name('jurusan.prodi');
        Route::get('/vue', [JurusanController::class, 'fakultas'])->name('jurusan.get');
    });

});

require __DIR__.'/auth.php';
