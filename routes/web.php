<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\TempCoachController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VerficationDocumentController;
use App\Http\Controllers\PayrollAccountController;
use App\Http\Controllers\CardController;

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

Route::get('/', [IndexController::class, 'index'])->name('index.index');

Route::prefix('admin')->group(function() {
    Route::get('/update_all_user_session_button', [AdminController::class, 'updateAllUserSessionButton'])->name('admin.update_all_user_session_button');
    Route::post('/update_all_user_session', [AdminController::class, 'updateAllUserSession'])->name('admin.update_all_user_session');
});

Route::prefix('temp_coaches')->group(function() {
    Route::get('/create/{coach_flag}', [TempCoachController::class, 'create'])->name('temp_coaches.create');
    Route::post('/save', [TempCoachController::class, 'save'])->name('temp_coach.save');
    Route::get('/redirect_to_register/{loginid}/{temp_code}/{coach_flag}', [TempCoachController::class, 'redirectToRegister'])->name('temp_coaches.redirect_to_register');
});

Route::prefix('coaches')->group(function() {
    Route::get('/login/{coach_flag}', [CoachController::class, 'login'])->name('coaches.login');
    Route::post('/auth', [CoachController::class, 'auth'])->name('coaches.auth');
    Route::get('/create/{loginid}/{coach_flag}', [CoachController::class, 'create'])->name('coaches.create');
    Route::post('/save', [CoachController::class, 'save'])->name('coaches.save');
    Route::get('/list', [CoachController::class, 'list'])->name('coaches.list')
        ->middleware('login');
    Route::get('/detail/{id}', [CoachController::class, 'detail'])->name('coaches.detail')
        ->middleware('login');
    Route::get('/logout', [CoachController::class, 'logout'])->name('coaches.logout')
        ->middleware('login');
    Route::get('/left', [CoachController::class, 'left'])->name('coaches.left')
        ->middleware('login');
});

Route::prefix('mypage')->group(function() {
    Route::get('/', [MypageController::class, 'index'])->name('mypage.index')
        ->middleware('login');
    Route::get('/edit', [MypageController::class, 'edit'])->name('mypage.edit')
        ->middleware('login');
    Route::post('/update', [MypageController::class, 'update'])->name('mypage.update');
    Route::post('/update_icon', [MypageController::class, 'updateIcon'])->name('mypage.update_icon');
    Route::post('/update_traininng', [MypageController::class, 'updateTraining'])->name('mypage.update_traininng');

    Route::get('/create_verification_document', [MypageController::class, 'createVerificationDocument'])->name('mypage.create_verification_document')
        ->middleware('login');
    Route::post('/save_verification_document', [MypageController::class, 'saveVerificationDocument'])->name('mypage.save_verification_document');

    Route::get('/create_payroll_account', [MypageController::class, 'createPayrollAccount'])->name('mypage.create_payroll_account')
        ->middleware('login');
    Route::post('/save_payroll_account', [MypageController::class, 'savePayrollAccount'])->name('mypage.save_payroll_account');
    Route::post('/save_yuutyo_payroll_account', [MypageController::class, 'saveYuutyoPayrollAccount'])->name('mypage.save_yuutyo_payroll_account');
    Route::post('/save_major_payroll_account', [MypageController::class, 'saveMajorPayrollAccount'])->name('mypage.save_major_payroll_account');
});

Route::prefix('messages')->group(function() {
    Route::get('/list', [MessageController::class, 'list'])->name('messages.list')
        ->middleware('login');
    Route::get('/talk/{opponent_id}/{opponent_name?}', [MessageController::class, 'talk'])->name('messages.talk')
        ->middleware('login');
    Route::post('/save', [MessageController::class, 'save'])->name('messages.save');
});

Route::prefix('reservations')->group(function() {
    Route::get('/list', [ReservationController::class, 'list'])->name('reservations.list')
        ->middleware('login');

    Route::get('/create/{id}', [ReservationController::class, 'create'])->name('reservations.create')
        ->middleware('login');
    Route::post('/to_confirm', [ReservationController::class, 'to_confirm'])->name('reservations.to_confirm');
    Route::get('/confirm', [ReservationController::class, 'confirm'])->name('reservations.confirm')
        ->middleware('login');
    Route::post('/save', [ReservationController::class, 'save'])->name('reservations.save');

    Route::get('/accept/{reservation_id}', [ReservationController::class, 'accept'])->name('reservations.accept')
        ->middleware('login');
    Route::get('/reject/{reservation_id}', [ReservationController::class, 'reject'])->name('reservations.reject')
        ->middleware('login');
    Route::get('/coach_cancel/{reservation_id}/{date}/{start_time}', [ReservationController::class, 'coach_cancel'])->name('reservations.coach_cancel')
        ->middleware('login');
    Route::get('/cancel/{reservation_id}/{date}/{start_time}', [ReservationController::class, 'cancel'])->name('reservations.cancel')
        ->middleware('login');

    Route::get('/create_review/{reservation_id}', [ReservationController::class, 'createReview'])->name('reservations.create_review')
        ->middleware('login');
    Route::post('/save_review', [ReservationController::class, 'saveReview'])->name('reservations.save_review');
});

Route::prefix('cards')->group(function() {
    Route::get('/create', [CardController::class, 'create'])->name('cards.create')
        ->middleware('login');
    Route::post('/register', [CardController::class, 'register'])->name('cards.register');
    Route::post('/update', [CardController::class, 'update'])->name('cards.update');
    Route::post('/re_register', [CardController::class, 'reRegister'])->name('cards.re_register');
    Route::get('/delete', [CardController::class, 'delete'])->name('cards.delete');
    Route::get('/charge', [CardController::class, 'charge'])->name('cards.charge');
});