<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProjectAssignmentController;
use App\Http\Controllers\ProjectController;

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

// login page
Route::get('/', function () {
    return view('login');
});

// go to employees page after login success
Route::post('/employees', [LoginController::class, 'index'])->name('login');

// authentication needed routes
Route::middleware(['loginAuth'])->group(function () {
    // employees
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees');
    
    Route::prefix('/employees')->name('employees.')->group(function () {
        Route::get('/create', [EmployeeController::class, 'create'])->name('create');
        Route::post('/store', [EmployeeController::class, 'store'])->name('store');
        Route::get('/show/{id}', [EmployeeController::class, 'show'])->name('show');
        Route::get('/edit/{id}', [EmployeeController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [EmployeeController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [EmployeeController::class, 'destroy'])->name('delete');
        Route::match(['get', 'post'], '/search', [EmployeeController::class, 'searchEmployees'])->name('search');
    });

    // download
    Route::post('/download', [DownloadController::class, 'checkDownloadType'])->name('download');

    // project assignments
    Route::get('/project-assignments/create', [ProjectAssignmentController::class, 'create'])->name('project-assignments.create');
    Route::post('/project-assignments/store', [ProjectAssignmentController::class, 'store'])->name('project-assignments.store');

    // projects
    Route::post('/projects/store', [ProjectController::class, 'store'])->name('projects.store');
    Route::delete('/projects/delete/{id}', [ProjectController::class, 'destroy'])->name('projects.delete');

    // download docs
    Route::get('/docs/download/{fileName}', [ProjectAssignmentController::class, 'downloadDocuments'])->name('docs.download');

    // language change
    Route::get('language/{locale}', [LanguageController::class, 'changeLanguage'])->name('set.language');

    // logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});