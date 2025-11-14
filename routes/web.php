<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\BreedingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReproductiveAnalyticsController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AccountController;
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
    Route::get('/reproductive-analytics', [ReproductiveAnalyticsController::class, 'index'])->name('reproductive-analytics');
    Route::get('/learning-courses', function (){
        // fournir toujours une variable $courses (vide par défaut)
        return view('learning-courses', ['courses' => []]);
    })->name('learning-courses');

    // Courses learning API (AJAX)
    Route::get('/api/courses', [CourseController::class, 'index'])->name('api.courses.index');
    Route::post('/learning-courses/upload', [CourseController::class, 'store'])->name('learning-courses.upload');

    // Account management (ajax)
    Route::post('/account/update', [AccountController::class, 'update'])->name('account.update');
    Route::post('/account/password-reset', [AccountController::class, 'sendResetLink'])->name('account.password.reset');

    // Animals CSV export / import
    Route::get('/animals/export', [App\Http\Controllers\AnimalController::class, 'exportCsv'])->name('animals.export');
    Route::post('/animals/import', [App\Http\Controllers\AnimalController::class, 'importCsv'])->name('animals.import');
});

// Public route used by Laravel password reset notification to build the reset URL.
// If you provide an external reset frontend, set PASSWORD_RESET_EXTERNAL_URL in .env (ex: https://auth.example.com/password-reset).
// Otherwise this closure returns a minimal informational HTML so the route exists and won't throw "Route [password.reset] not defined".
Route::get('/password/reset/{token}', function ($token) {
    $external = env('PASSWORD_RESET_EXTERNAL_URL');
    if ($external) {
        $url = rtrim($external, '/') . '?token=' . urlencode($token);
        return redirect()->away($url);
    }
    // Minimal HTML response (no view file required)
    return response()->make(
        '<!doctype html><html><head><meta charset="utf-8"><title>Réinitialiser le mot de passe</title></head><body style="font-family:Arial,Helvetica,sans-serif;padding:2rem;">'
        . '<h2>Réinitialisation du mot de passe</h2>'
        . '<p>Suivez le lien fourni par votre email pour réinitialiser votre mot de passe.</p>'
        . '<p>Token (pour débogage) : <code>' . e($token) . '</code></p>'
        . '</body></html>',
        200,
        ['Content-Type' => 'text/html']
    );
})->name('password.reset');

