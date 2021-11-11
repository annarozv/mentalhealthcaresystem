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
Route::post('user/{id}/deactivate', [UserController::class, 'deactivate'])->name('user.deactivate');

// Patient routes
Route::get('patient/{id}/info', [PatientController::class, 'show']);
Route::get('patient/new', [PatientController::class, 'create']);
Route::post('patient/store', [PatientController::class, 'store'])->name('patient.store');
Route::get('patient/edit', [PatientController::class, 'edit']);
Route::post('patient/{id}/update', [PatientController::class, 'update'])->name('patient.update');
Route::post('patient/{id}/remove', [PatientController::class, 'destroy'])->name('patient.remove');
Route::get('my/therapists', [PatientController::class, 'getTherapists'])->name('patient.therapists');
Route::get('my/requests', [PatientController::class, 'getRequests'])->name('patient.requests');
Route::post('connect/{therapistId}', [PatientController::class, 'connectWithTherapist'])->name('connect.therapist');
Route::post('disconnect/{therapistId}', [PatientController::class, 'disconnectTherapist'])->name('disconnect.therapist');
Route::get('review/{therapistId}/create', [ReviewController::class, 'create'])->name('review.create');
Route::post('review/{therapistId}/store/{patientId}', [ReviewController::class, 'store'])->name('review.store');
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
Route::get('illness/{id}/add_symptoms', [IllnessController::class, 'displayAvailableSymptoms'])->name('illness.display_symptoms');
Route::post('illness/{id}/add_symptoms_save', [IllnessController::class, 'addSymptoms'])->name('illness.add_symptoms');
Route::post('illness/{id}/remove/{symptomId}', [IllnessController::class, 'removeSymptom'])->name('illness.remove_symptom');
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
