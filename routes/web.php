<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectReportController;

// Redirect root to projects index
Route::get('/', [ProjectController::class, 'index'])->name('projects.index');

// Project routes
Route::prefix('projects')->group(function () {
    Route::post('/', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::put('/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Transactions
    Route::post('/{project}/transactions', [ProjectController::class, 'addTransaction'])->name('projects.transactions.store');
    Route::delete('/{project}/transactions/{transaction}', [ProjectController::class, 'deleteTransaction'])->name('projects.transactions.destroy');

    // Category summary JSON endpoint
    Route::get('/{project}/category-summary', [ProjectController::class, 'getCategorySummary'])->name('projects.category-summary');
    
    // Proof image viewing
    Route::get('/{project}/transaction/{transaction}/proof', [ProjectController::class, 'viewProof'])->name('projects.transactions.proof');
    
    // AJAX transactions endpoint
    Route::get('/{project}/transactions/json', [ProjectController::class, 'getTransactionsJson'])->name('projects.transactions.json');
});

// Report routes using ProjectReportController
// Report routes using ProjectReportController
Route::prefix('projects/{project}/report')->name('projects.report.')->group(function () {
    Route::get('excel', [ProjectReportController::class, 'downloadExcel'])->name('excel');
    Route::get('pdf', [ProjectReportController::class, 'downloadPdf'])->name('pdf');
    Route::get('word', [ProjectReportController::class, 'downloadWord'])->name('word');
});