<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AmendmentController;
use App\Http\Controllers\ApprovalController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DeemableJobsController;
use App\Http\Controllers\DesignerController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProofController;
use App\Http\Controllers\ProofingCompaniesController;
use App\Http\Controllers\ProofingJobController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\ReportsMaintenanceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckUserRole;

Route::get('/', function () {
    return view('auth.login');
});


// Admin, Super Admin, and Designer routes
Route::middleware(['auth'])->group(function () {

    // Route::get('/dashboard', function () {return view('dashboard');})->name('dashboard');

    // Route::get('/customers-landing', [CustomerController::class, 'landing'])->name('customers.landing');

    Route::middleware(['auth', 'redirectBasedOnRole'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        Route::get('/customers-landing', [CustomerController::class, 'landing'])->name('customers.landing');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // users resource routes
    Route::resource('users', UserController::class);


    // customers resource routes
    Route::resource('customers', CustomerController::class)->middleware('access.level:1');
    Route::get('customers/{customer}/confirm', [CustomerController::class, 'confirm'])->name('customers.confirm');

    // Proofing Jobs routes
    Route::get('customers/{customerId}/proofing_jobs', [ProofingJobController::class, 'index'])->name('proofing_jobs.index')->middleware('access.level:1');
    Route::get('customers/{customerId}/proofing_jobs/create', [ProofingJobController::class, 'create'])->name('proofing_jobs.create')->middleware('access.level:1');
    Route::post('customers/{customerId}/proofing_jobs', [ProofingJobController::class, 'store'])->name('proofing_jobs.store')->middleware('access.level:1');
    Route::get('customers/{customerId}/proofing_jobs/{proofingJob}/confirm', [ProofingJobController::class, 'showSendProof'])->name('proofing_jobs.confirm')->middleware('access.level:1');
    Route::get('/proofing_jobs/{proofingJob}', [ProofingJobController::class, 'show'])->name('proofing_jobs.show')->middleware('access.level:1');
    Route::get('customers/{customerId}/proofing_jobs/{proofingJob}/edit', [ProofingJobController::class, 'edit'])->name('proofing_jobs.edit')->middleware('access.level:1');
    Route::put('customers/{customerId}/proofing_jobs/{proofingJob}', [ProofingJobController::class, 'update'])->name('proofing_jobs.update')->middleware('access.level:1');
    Route::delete('customers/{customerId}/proofing_jobs/{proofingJob}', [ProofingJobController::class, 'destroy'])->name('proofing_jobs.destroy')->middleware('access.level:2');


    // proofs resource routes
    Route::resource('proofs', ProofController::class)->except(['create', 'store', 'destroy'])->middleware('access.level:1');
    Route::delete('/proofs/{customerId}/{proof}', [ProofController::class, 'destroy'])->name('proofs.destroy')->middleware('access.level:1');
    Route::get('/proofs/create/{jobId}/{customerId}', [ProofController::class, 'create'])->name('proofs.create')->middleware('access.level:1');
    Route::post('proofs/store', [ProofController::class, 'store'])->name('proofs.store')->middleware('access.level:1');
    //Route::post('proofs/update', [ProofController::class, 'update'])->name('proofs.update');
    Route::get('proofs/{proof}/confirm', [ProofController::class, 'confirm'])->name('proofs.confirm')->middleware('access.level:1');
    Route::post('proofs/{proof}/send-proof', [ProofController::class, 'sendProofEmail'])->name('proofs.sendProofEmail')->middleware('access.level:1');
    Route::post('/proofs/{proof}/resend', [ProofController::class, 'resendProofEmail'])->name('proofs.resendProofEmail')->middleware('access.level:1');


    // amendments resource routes#
    Route::get('customer/{customerId}/amendments', [AmendmentController::class, 'index'])->name('amendments.index')->middleware('access.level:1');
    Route::get('amendments/create', [AmendmentController::class, 'create'])->name('amendments.create')->middleware('access.level:1');
    Route::get('amendments/{amendment}', [AmendmentController::class, 'show'])->name('amendments.show')->middleware('access.level:1');
    Route::resource('amendments', AmendmentController::class)->except(['index', 'show'])->middleware('access.level:1');

    // approvals resource routes
    Route::resource('approvals', ApprovalController::class)->middleware('access.level:1');

    //activities routes
    // Filter activities by date range
    Route::get('activities/filter', [ActivityController::class, 'index'])->name('activities.filter')->middleware('access.level:1');

    // Standard resource routes
    Route::resource('activities', ActivityController::class)->middleware('access.level:1');

    // User specific activities
    Route::get('activities/user/{user}', [ActivityController::class, 'index'])->middleware('access.level:1')
        ->name('activities.user');

    // Job specific activities
    Route::get('/activities/job/{id}', [ActivityController::class, 'jobActivities'])->middleware('access.level:1')
        ->name('activities.job');

    // Email resource routes
    Route::resource('emails', EmailController::class)->middleware('access.level:1');

    // Designers resource routes
    Route::resource('designers', DesignerController::class)->middleware('access.level:1');

    // Proofing Companies resource routes
    Route::resource('proofing_companies', ProofingCompaniesController::class)->middleware('access.level:1');

    // Reminder routes
    Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
    Route::post('/reminders/process', [ReminderController::class, 'processReminders'])->name('reminders.process')->middleware('access.level:1');
    Route::get('/reminders/history', [ReminderController::class, 'showReminderHistory'])->name('reminders.history')->middleware('access.level:1');
    Route::get('/reminders/download_reminders', [ReminderController::class, 'downloadCsv'])->name('reminders.download_reminders')->middleware('access.level:1');
    Route::get('/reminders/download_history', [ReminderController::class, 'downloadCsv'])->name('reminders.download_history')->middleware('access.level:1');

    // Deemable jobs routes
    Route::get('/deemable_jobs', [DeemableJobsController::class, 'index'])->name('deemable_jobs.index')->middleware('access.level:1');
    Route::post('/deemable_jobs/process', [DeemableJobsController::class, 'processDeemableJobs'])->name('deemable_jobs.process')->middleware('access.level:1');
    Route::any('/deemable_jobs/show_deemed', [DeemableJobsController::class, 'showDeemedJobs'])->name('deemable_jobs.show_deemed')->middleware('access.level:1');

    // Reports routes
    Route::get('/reports/proofs_report', [ReportsController::class, 'proofs_report'])->name('reports.proofs_report')->middleware('access.level:1');
    Route::get('/reports/amendments_report', [ReportsController::class, 'amendments_report'])->name('reports.amendments_report')->middleware('access.level:1');
    Route::get('/reports/approvals_report', [ReportsController::class, 'approvals_report'])->name('reports.approvals_report')->middleware('access.level:1');
    Route::get('/reports/download/{report_view}', [ReportsController::class, 'downloadCsv'])->name('reports.download')->middleware('access.level:1');
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports.index')->middleware('access.level:1');
    Route::get('/reports/{id}', [ReportsController::class, 'viewReport'])->name('reports.view')->middleware('access.level:1');
    Route::get('/reports/customer/{customerId}/{reportName}', [ReportsController::class, 'customerReport'])->name('reports.customer_activity')->middleware('access.level:1');

    // reports maintenance routes
    Route::resource('reports-maintenance', ReportsMaintenanceController::class)->parameters(['reports-maintenance' => 'report'])->middleware('access.level:1');


    // customers proofing routes
    Route::get('/customers/view-proof/{id}', [CustomerController::class, 'viewProof'])->name('customers.view_proof');
    Route::post('/customers/submit-amendment', [CustomerController::class, 'submitAmendment'])->name('customers.submit_amendment');
    Route::post('/customers/submit-approval', [CustomerController::class, 'submitApproval'])->name('customers.submit_approval');
    Route::get('/proofs/{id}/download', [CustomerController::class, 'downloadProof'])->name('proofs.download');


    // phpinfo route for troubleshooting
    Route::get('/phpinfo', function () {
        phpinfo();
    })->name('phpinfo');

});

require __DIR__ . '/auth.php';


