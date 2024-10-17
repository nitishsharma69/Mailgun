<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MailgunWebhookController;


Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

Route::post('/mail-campaign',[ContactController::class,'uploadContact']);
Route::get('/mail-initiate', function(){

return view('upload');

});

Route::get('/test-email', function () {
    Mail::raw('This is a test email using Mailgun.', function ($message) {
        $message->to('nitishsharma069@gmail.com')
                ->subject('Test Email');
    });

    return 'Test email sent!';
});


Route::get('/bounce-check', function(){

    return view('bounce');
    
    });
Route::post('/mailgun/bounces', [MailgunWebhookController::class, 'downloadBouncedEmailsAsExcel']);