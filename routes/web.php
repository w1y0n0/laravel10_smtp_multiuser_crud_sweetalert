<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\UproleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::view('/', 'frontend.index');
    Route::get('/sesi', [AuthController::class, 'index'])->name('auth'); // {{ route('auth) }}
    Route::post('/sesi', [AuthController::class, 'login']);
    Route::get('/reg', [AuthController::class, 'create'])->name('registrasi');
    Route::post('/reg', [AuthController::class, 'register']);
    Route::get('/verify/{verify_key}', [AuthController::class, 'verify']);
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('/home', '/user'); // redirect dari '/home' jadi '/user'
    Route::get('/admin', [AdminController::class, 'index'])->name('admin')->middleware('aksesUser:admin');
    Route::get('/user', [UserController::class, 'index'])->name('user')->middleware('aksesUser:user');

    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa');
    Route::get('/mahasiswa/tambah', [MahasiswaController::class, 'tambah']);
    Route::get('/mahasiswa/edit/{id}', [MahasiswaController::class, 'edit']);
    Route::post('/mahasiswa/hapus/{id}', [MahasiswaController::class, 'hapus']);

    Route::get('/user-management', [UserManagementController::class, 'index'])->name('user.management');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // new
    Route::post('/tambahdama', [MahasiswaController::class, 'create']);
    Route::post('/editdama', [MahasiswaController::class, 'change']);

    Route::get('/tambahuser', [UserManagementController::class, 'tambah']);
    Route::get('/edituc/{id}', [UserManagementController::class, 'edit']);
    Route::post('/hapusuc/{id}', [UserManagementController::class, 'hapus']);
    Route::post('/tambahuser', [UserManagementController::class, 'create']);
    Route::post('/edituc', [UserManagementController::class, 'change']);

    Route::post('/uprole/{id}', [UproleController::class, 'index']);
});
