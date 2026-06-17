<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BetalingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstructeurController;
use App\Http\Controllers\LesrijpakketController;
use App\Http\Controllers\VoertuigController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/registreren', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registreren', [AuthController::class, 'register'])->name('register.store');
});

Route::view('/', 'landing')->name('landing');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', HomeController::class)->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/accounts', [AccountController::class, 'index'])
        ->middleware('admin.only')
        ->name('accounts.index');
    Route::get('/accounts/aanmaken', [AccountController::class, 'create'])
        ->middleware('admin.only')
        ->name('accounts.create');
    Route::post('/accounts', [AccountController::class, 'store'])
        ->middleware('admin.only')
        ->name('accounts.store');

    Route::get('/lespakketten', [LesrijpakketController::class, 'index'])->name('lesrijpakketten.index');
    Route::get('/instructeurs', [InstructeurController::class, 'index'])->name('instructeurs.index');
    Route::get('/instructeurs/{instructeur}/voertuigen', [VoertuigController::class, 'index'])->name('instructeurs.voertuigen.index');
    Route::middleware('can.manage.payments')->group(function (): void {
        Route::get('/betalingen', [BetalingController::class, 'index'])->name('betalingen.index');
        Route::post('/betalingen', [BetalingController::class, 'store'])->name('betalingen.store');
    });

    Route::middleware('can.manage.vehicles')->group(function (): void {
        Route::get('/instructeurs/{instructeur}/voertuigen/beschikbaar', [VoertuigController::class, 'beschikbaar'])->name('instructeurs.voertuigen.beschikbaar');
        Route::get('/instructeurs/{instructeur}/voertuigen/{voertuig}/wijzigen', [VoertuigController::class, 'edit'])->name('instructeurs.voertuigen.edit');
        Route::put('/instructeurs/{instructeur}/voertuigen/{voertuig}', [VoertuigController::class, 'update'])->name('instructeurs.voertuigen.update');
    });
});
