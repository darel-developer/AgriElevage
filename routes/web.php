<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\BreedingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReproductiveAnalyticsController;
use Illuminate\Support\Facades\View;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('verify.otp.form');
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp');
    Route::post('/verify-otp/resend', [AuthController::class, 'resendOtp'])->name('verify.otp.resend');
    Route::get('/dashboard', [AnimalController::class, 'dashboard'])->name('dashboard');
    Route::get('/calendar', function (){
        return view('calendar');
    })->name('calendar');
    Route::get('/chatbot', [ChatbotController::class, 'show'])->name('chatbot');
    Route::post('/chatbot/message', [ChatbotController::class, 'message'])->name('chatbot.message');
    Route::post('/animals', [AnimalController::class, 'store'])->name('animals.store');
    Route::get('/animals', [AnimalController::class, 'index'])->name('animals.index');
    Route::delete('/animals/{animal}', [AnimalController::class, 'destroy'])->name('animals.destroy');
    Route::put('/animals/{animal}', [AnimalController::class, 'update'])->name('animals.update');
    Route::get('/suivi-individuel', [AnimalController::class, 'suiviIndividuel'])->name('suivi.individuel');
    Route::get('/animals/{animal}/details', [AnimalController::class, 'details'])->name('animals.details');
    Route::get('/breeding', [BreedingController::class, 'showForm'])->name('breeding.form');
    Route::post('/breeding', [BreedingController::class, 'store'])->name('breeding.store');
    Route::get('/breeding/events', [BreedingController::class, 'events'])->name('breeding.events');
    Route::get('/breeding-planning', [BreedingController::class, 'showForm'])->name('breeding-planning');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/all', [EventController::class, 'events'])->name('events.all');
    Route::get('/events/{date}/details', [EventController::class, 'details'])->name('events.details');
    Route::get('/health-diagnostic', function () {
        return view('health-diagnostic');
    })->name('health.diagnostic');
    Route::get('/mon-compte', function () {
        return view('account');
    })->name('account');
    Route::get('/genetique', function () {
        return view('genetique');
    })->name('genetique');
    Route::get('/reproductive-analytics', [ReproductiveAnalyticsController::class, 'index'])->name('reproductive.analytics');
});

