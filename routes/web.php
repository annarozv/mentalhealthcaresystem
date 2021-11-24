<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IllnessController;
use App\Http\Controllers\SymptomController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TherapistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DiaryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ModeratorController;

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

// Welcome page will redirect to custom view instead of default laravel view
Route::get('/', function () {
    return view('library');
})->name('library');

Auth::routes();

// Route for language switching
Route::get('lang/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);

    return redirect()->back();
});

Route::get('home', [HomeController::class, 'index'])->name('home');

// User related routes
Route::get('profile', [HomeController::class, 'profile'])->name('profile');
Route::post('user/{id}/deactivate/self', [UserController::class, 'deactivate'])->name('user.deactivate');

// Admin and moderator routes
Route::get('moderators', [ModeratorController::class, 'index']);
Route::get('moderator/add', [ModeratorController::class, 'create']);
Route::post('moderator/store', [ModeratorController::class, 'store'])->name('moderator.store');
Route::post('moderator/{id}/remove', [ModeratorController::class, 'destroy'])->name('moderator.remove');

Route::get('system/users', [ModeratorController::class, 'getSystemUsers']);
Route::post('user/{id}/deactivate', [ModeratorController::class, 'deactivateUser'])->name('deactivate.user');
Route::post('user/{id}/reactivate', [ModeratorController::class, 'reactivateUser'])->name('reactivate.user');
Route::post('system/users/search', [ModeratorController::class, 'filterUsers'])->name('users.search');
Route::get('system/users/search', function () {
    return redirect('system/users');
});

// Patient routes
Route::get('patient/{id}/info', [PatientController::class, 'show']);
Route::get('patient/new', [PatientController::class, 'create']);
Route::post('patient/store', [PatientController::class, 'store'])->name('patient.store');
Route::get('patient/edit', [PatientController::class, 'edit']);
Route::post('patient/{id}/update', [PatientController::class, 'update'])->name('patient.update');
Route::post('patient/{id}/remove', [PatientController::class, 'destroy'])->name('patient.remove');
Route::get('my/therapists', [PatientController::class, 'getTherapists'])->name('patient.therapists');
Route::get('my/requests', [PatientController::class, 'getRequests'])->name('patient.requests');
Route::post('connect/therapist/{therapistId}', [PatientController::class, 'connectWithTherapist'])
    ->name('connect.therapist');
Route::post('disconnect/therapist/{therapistId}', [PatientController::class, 'disconnectTherapist'])
    ->name('disconnect.therapist');
Route::post('request/feedback/{therapistId}', [PatientController::class, 'requestFeedback'])
    ->name('request.feedback');
Route::post('request/{id}/remove/patient', [PatientController::class, 'removeFeedbackRequest'])
    ->name('patient.remove.request');
Route::get('review/{therapistId}/create', [ReviewController::class, 'create'])->name('review.create');
Route::post('review/{therapistId}/store/{patientId}', [ReviewController::class, 'store'])
    ->name('review.store');
Route::get('review/{id}/edit', [ReviewController::class, 'edit']);
Route::post('review/{id}/update', [ReviewController::class, 'update'])->name('review.update');
Route::post('review/{id}/remove', [ReviewController::class, 'destroy'])->name('review.delete');

// Therapist routes
Route::get('therapists', [TherapistController::class, 'index'])->name('therapist.all');
Route::get('therapist/{id}/info', [TherapistController::class, 'show']);
Route::get('therapist/new', [TherapistController::class, 'create']);
Route::post('therapist/store', [TherapistController::class, 'store'])->name('therapist.store');
Route::get('therapist/edit', [TherapistController::class, 'edit']);
Route::post('therapist/{id}/update', [TherapistController::class, 'update'])->name('therapist.update');
Route::post('therapist/{id}/remove', [TherapistController::class, 'destroy'])->name('therapist.remove');
Route::post('therapists/search', [TherapistController::class, 'filter'])->name('therapists.filter');
Route::get('therapists/search', function () {
    return redirect('therapists');
});
Route::get('therapist/patients', [TherapistController::class, 'getPatients'])->name('therapist.patients');
Route::get('therapist/requests', [TherapistController::class, 'getRequests'])->name('therapist.requests');
Route::post('disconnect/patient/{patientId}', [TherapistController::class, 'disconnectPatient'])
    ->name('disconnect.patient');
Route::post('approve/feedback/{id}', [TherapistController::class, 'approveRequest'])
    ->name('therapist.approve.request');
Route::post('request/{id}/remove/therapist', [TherapistController::class, 'refuseRequest'])
    ->name('therapist.remove.request');
Route::get('therapist/find', [TherapistController::class, 'findTherapistsIndividually']);

// Diary routes
Route::get('diary/{patientId}', [DiaryController::class, 'index'])->name('diary');
Route::get('record/{id}/info', [DiaryController::class, 'show']);
Route::get('diary/{patientId}/new', [DiaryController::class, 'create']);
Route::post('diary/{patientId}/store', [DiaryController::class, 'store'])->name('record.store');
Route::get('record/{id}/edit', [DiaryController::class, 'edit']);
Route::post('record/{id}/update', [DiaryController::class, 'update'])->name('record.update');
Route::post('record/{id}/delete', [DiaryController::class, 'destroy'])->name('record.delete');
Route::post('diary/{patientId}/clear', [DiaryController::class, 'deleteAllRecords'])->name('records.delete.all');

Route::get('comment/{recordId}/new', [CommentController::class, 'create']);
Route::post('comment/{recordId}/store', [CommentController::class, 'store'])->name('comment.store');
Route::get('comment/{id}/edit', [CommentController::class, 'edit']);
Route::post('comment/{id}/update', [CommentController::class, 'update'])->name('comment.update');
Route::post('comment/{id}/remove', [CommentController::class, 'destroy'])->name('comment.delete');

// Routes for illnesses
Route::get('illnesses', [IllnessController::class, 'index'])->name('illness.all');
Route::get('illness/{id}/details', [IllnessController::class, 'show'])->name('illness.details');
Route::post('illnesses/search', [IllnessController::class, 'filter'])->name('illnesses.filter');
Route::get('illnesses/search', function () {
    return redirect('illnesses');
});
Route::get('illness/create', [IllnessController::class, 'create']);
Route::post('illness/store', [IllnessController::class, 'store'])->name('illness.store');
Route::get('illness/{id}/edit', [IllnessController::class, 'edit'])->name('illness.edit');
Route::post('illness/{id}/update', [IllnessController::class, 'update'])->name('illness.update');
Route::get('illness/{id}/add_symptoms', [IllnessController::class, 'displayAvailableSymptoms'])
    ->name('illness.display_symptoms');
Route::post('illness/{id}/add_symptoms_save', [IllnessController::class, 'addSymptoms'])
    ->name('illness.add_symptoms');
Route::post('illness/{id}/remove/{symptomId}', [IllnessController::class, 'removeSymptom'])
    ->name('illness.remove_symptom');
Route::post('illness/{id}/delete', [IllnessController::class, 'destroy'])->name('illness.remove');

// Routes for symptoms
Route::get('symptoms', [SymptomController::class, 'index'])->name('symptom.all');
Route::get('symptom/{id}/details', [SymptomController::class, 'show'])->name('symptom.details');
Route::post('symptoms/search', [SymptomController::class, 'filter'])->name('symptoms.filter');
Route::get('symptoms/search', function () {
    return redirect('symptoms');
});
Route::get('symptom/create', [SymptomController::class, 'create']);
Route::post('symptom/store', [SymptomController::class, 'store'])->name('symptom.store');
Route::get('symptom/{id}/edit', [SymptomController::class, 'edit'])->name('symptom.edit');
Route::post('symptom/{id}/update', [SymptomController::class, 'update'])->name('symptom.update');
Route::post('symptom/{id}/delete', [SymptomController::class, 'destroy'])->name('symptom.remove');
