<?php

use App\Http\Controllers\ExamController;
use App\Http\Controllers\ManageQuestionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExamController::class, 'index'])->name('exams.index');
Route::post('/topics', [ExamController::class, 'storeTopic'])->name('topics.store');
Route::get('/exams/submissions/count', [ExamController::class, 'submissionsCount'])->name('exams.submissions.count');
Route::get('/exams/stats', [ExamController::class, 'stats'])->name('exams.stats');
Route::get('/exams/{topic}', [ExamController::class, 'show'])->name('exams.show');
Route::post('/exams/{topic}', [ExamController::class, 'submit'])->name('exams.submit');

// Quiz management for a specific topic
Route::get('/exams/{topic}/manage', [ManageQuestionsController::class, 'index'])->name('exams.manage');
Route::post('/exams/{topic}/questions', [ManageQuestionsController::class, 'store'])->name('exams.questions.store');
Route::get('/exams/{topic}/questions/{index}/edit', [ManageQuestionsController::class, 'edit'])->name('exams.questions.edit');
Route::put('/exams/{topic}/questions/{index}', [ManageQuestionsController::class, 'update'])->name('exams.questions.update');
Route::delete('/exams/{topic}/questions/{index}', [ManageQuestionsController::class, 'destroy'])->name('exams.questions.destroy');
