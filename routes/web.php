<?php

use App\Http\Controllers\ExamController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExamController::class, 'index'])->name('exams.index');
Route::get('/exams/{topic}', [ExamController::class, 'show'])->name('exams.show');
Route::post('/exams/{topic}', [ExamController::class, 'submit'])->name('exams.submit');
