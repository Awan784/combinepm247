<?php

use App\Http\Controllers\auth\UserController;
use App\Http\Controllers\EngineerController;
use App\Http\Controllers\JobController;
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

Route::get('/google-test', function () {
    try {
        $ch = curl_init('https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);
        return $err ?: 'OK';
    } catch (\Exception $e) {
        return $e->getMessage();
    }
});

Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    Artisan::call('route:clear');

    return response()->json(['message' => 'Cache and configuration cleared successfully']);
});
Route::get('/upload', function(){
    return view('upload');
});
Route::post('/upload', [EngineerController::class, 'uploadCSV'])->name('uploadCSV');

Route::get('/send-email', [JobController::class, 'sendEmail']);
Route::get('/dropbox/refresh-token', [JobController::class, 'refreshToken']);

Route::get('/dropbox/authorize', [JobController::class, 'authorizeDropbox'])->name('dropbox.authorize');
Route::get('/dropbox/callback', [JobController::class, 'handleCallback'])->name('dropbox.callback');




Route::get('/', [UserController::class,"index"])->name('login');;
Route::post('/', [UserController::class,"processAuthRequest"]);

Route::middleware('auth')->group(function() {

    Route::group(["prefix" => "dashboard"],function(){
        Route::get('engineer', [UserController::class, 'EngineerDashboard'])->name('dashboard.engineer');
        Route::get('contract', [UserController::class, 'ContractDashboard'])->name('dashboard.contract');
        Route::get('assign', [UserController::class, 'AssignDashboard'])->name('dashboard.assign');
    });

    Route::get('mails-check1', [JobController::class, 'listEmails1'])->name('mails.check1');

    Route::get("logout",[UserController::class,"logout"]);

    Route::get('login/google', [UserController::class, 'redirectToGoogle'])->name('login.google');
    Route::get('callback', [UserController::class, 'handleGoogleCallback']);
    Route::get('mails-check', [JobController::class, 'listEmails'])->name('mails.check');
    Route::get('gmail-login-check', [JobController::class, 'GmailCheck'])->name('gmail.login.check');
    Route::get('gmail-error-check', [JobController::class, 'GmailErrorCheck'])->name('gmail.error.check');

    Route::resource('engineers',EngineerController::class);
    Route::group(["prefix" => "engineers"],function(){
        Route::get("availability/{id}",[EngineerController::class,"showAvailabilityForm"]);
        Route::put("availability/{id}",[EngineerController::class,"updateAvailabilityValue"]);
        Route::post("add-availability",[EngineerController::class,"AddAvailability"]);
        Route::get("remove/{id}",[EngineerController::class,"RemoveEngineerAvailability"]);
    });
    
  

    Route::resource('jobs',JobController::class);
    Route::group(["prefix" => "jobs"],function(){
        Route::get('assign_engineer/{id}',[JobController::class, 'AssignEngineer'])->name('job.assign_engineer');
        Route::post('assign_engineer/{id}',[JobController::class, 'AssignEngineerPost'])->name('job.assign_engineer.post');
        Route::get('assign_agent/{id}',[JobController::class, 'AssignAgent'])->name('job.assign_agent');
        Route::post('assign_agent/{id}',[JobController::class, 'AssignAgentPost'])->name('job.assign_agent.post');
        Route::get('assign_hand_over/{id}',[JobController::class, 'AssignHandover'])->name('job.assign_hand_over');
        Route::post('assign_hand_over/{id}',[JobController::class, 'AssignHandoverPost'])->name('job.assign_hand_over.post');
        Route::post('accept/{id}',[JobController::class, 'AcceptJob'])->name('job.accept');
        Route::get('reject/{id}',[JobController::class, 'RejectJob'])->name('job.reject');
        Route::get('investigation/{id}',[JobController::class, 'Investigation'])->name('job.investigation');
        Route::post('investigation/store',[JobController::class, 'InvestigationStore'])->name('job.investigation_store');
        Route::get('investigation/preview/{id}', [JobController::class, 'preview'])->name('investigation.preview');
        Route::get('investigation/show-pdf/{id}', [JobController::class, 'showPdf'])->name('investigation.showPdf');
      
      //Completion Form Routes
      Route::get('completion/{id}',[JobController::class, 'completion'])->name('job.completion');
      Route::post('completion/store',[JobController::class, 'CompletionStore'])->name('job.completion_store');
      Route::get('completion/investigation/{id}',[JobController::class, 'jobSheetPdf'])->name('job.completion_investigation.pdf');
    });
    Route::get('investigation/job', [JobController::class, 'investigationPdf'])->name('investigation.pdf.preview');
    Route::get('investigation/job/preview/{id}', [JobController::class, 'investigationPdfPreview'])->name('investigation.job.preview.pdf');
    Route::get('completion/job/preview/{id}', [JobController::class, 'completionPdfPreview'])->name('completion.job.preview.pdf');
    // contract & payment route
    Route::get("contracts",[JobController::class,"Contracts"]);
    Route::get("contracts/sent/{id}",[JobController::class,"ContractSent"]);
    Route::get("contracts/received/{id}",[JobController::class,"ContractReceived"]);
    Route::get("payments",[JobController::class,"Payments"]);
    Route::get("payments/sent/{id}",[JobController::class,"PaymentSent"]);
    Route::get("payments/received/{id}",[JobController::class,"PaymentReceived"]);

    Route::resource("users",\App\Http\Controllers\UserController::class);

    Route::get('dashboard/latest_data',[JobController::class,"latestData"]);
    Route::get('tv_dashboard/latest_data',[JobController::class,"latestTvData"]);
    Route::get('dashboard/contract_latest_data',[JobController::class,"contractLatestData"]);
    Route::get('tv_dashboard/contract_latest_data',[JobController::class,"contractLatestTvData"]);
    // Route::get("tv-view",[UserController::class,"ShowTvView"]);
    Route::group(["prefix" => "tv-view"],function(){
        Route::get('engineer', [UserController::class, 'EngineerTv'])->name('tv.engineer');
        Route::get('contract', [UserController::class, 'ContractTv'])->name('tv.contract');
        Route::get('assign', [UserController::class, 'AssignTv'])->name('tv.assign');
    });

    Route::get("search",[EngineerController::class,"SearchEngineer"]);
    Route::get('job_search/{id}',[EngineerController::class, 'JobSearch'])->name('job.assign_agent_search');
    Route::get('available-today',[EngineerController::class,"AvailableToday"]);
    Route::get('job-type-engineers',[EngineerController::class,"JobTypeEngineers"]);
    Route::get("weekly-available-engineers",[EngineerController::class,"WeeklyAvailableEngineers"]);
  // routes/web.php
    Route::post('/investigation/{id}/delete-image', [JobController::class, 'deleteImage'])->name('investigation.delete-image');
    Route::post('/completion/{id}/delete-image', [JobController::class, 'completionDeleteImage'])->name('completion.delete-image');
    
    Route::delete('/investigation/{id}/delete-video', [JobController::class, 'deleteVideo']) ->name('investigation.delete_video');
    Route::delete('/completion/{id}/delete-video', [JobController::class, 'completionDeleteVideo']) ->name('completion.delete_video');
    



});
