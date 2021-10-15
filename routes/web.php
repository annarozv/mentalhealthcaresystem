<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IllnessController;
use App\Http\Controllers\SymptomController;

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

// route for language switching
Route::get('lang/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);

    return redirect()->back();
});

Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

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
