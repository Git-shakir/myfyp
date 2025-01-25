<?php
//myproject\routes\web.php
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Firebase\AuthController;
use App\Http\Controllers\Firebase\animalDataController;
use App\Http\Controllers\ReportController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('loginpage');
Route::post('/login', [AuthController::class, 'login'])->name('firebase.login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('registerpage');
Route::post('/register', [AuthController::class, 'register'])->name('firebase.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [AuthController::class, 'profile'])->name('authentication.profile');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot-password-form');
Route::post('/forgot-password', [AuthController::class, 'sendResetPasswordEmail'])->name('send-reset-password-email');


Route::get('animalsData', [animalDataController::class, 'index'])->name('list-animalData');
Route::get('add-animalData', [animalDataController::class, 'create'])->name('add-animalData');
Route::post('add-animalData', [animalDataController::class, 'store'])->name('add-animalData-post');
Route::get('edit-animalData/{livestockUid}', [animalDataController::class, 'edit'])->name('edit-animalData');
Route::put('update-animalData/{livestockUid}', [animalDataController::class, 'update'])->name('update-animalData');
Route::delete('delete-animalData/{livestockUid}', [animalDataController::class, 'destroy']);

Route::delete('/delete-log/{key}', [ReportController::class, 'deleteLog'])->name('delete-log');
Route::delete('/clear-all-logs', [ReportController::class, 'clearAllLogs'])->name('clear-all-logs');


Route::get('get-animal-history/{animalId}', [animalDataController::class, 'getAnimalHistory']);
Route::get('get-phy-examination/{animalId}', [AnimalDataController::class, 'getPhyExamination'])->name('get-phy-examination');

Route::get('rfid-Logs', [animalDataController::class, 'getUid']);
Route::get('/activity-logs', [animalDataController::class, 'showActivityLogs']);
Route::get('/get-livestock-details/{animalId}', [animalDataController::class, 'getLivestockDetails']);
Route::get('/activity-logs', [animalDataController::class, 'showActivityLogs'])->name('activity.logs');
Route::get('/reports', [animalDataController::class, 'reports'])->name('reports');

Route::get('/animal/checkup/{livestockUid}', [animalDataController::class, 'checkup'])->name('checkup-animal');
Route::post('/animal/checkup', [animalDataController::class, 'storeCheckup'])->name('add-checkup-post');

Route::get('/get-checkup-data/{livestockUid}', [animalDataController::class, 'getCheckupData']);

Route::get('/generate-pdf', [ReportController::class, 'generatePdf'])->name('generate-pdf');



Route::get('/check-tag-trigger', function () {
    $firebase = app('firebase.database');

    $newUid = $firebase->getReference('/triggers/new_uid')->getValue();
    $editUid = $firebase->getReference('/triggers/edit_uid')->getValue();

    // Clear triggers after reading
    if ($newUid) {
        $firebase->getReference('/triggers/new_uid')->remove();
    }
    if ($editUid) {
        $firebase->getReference('/triggers/edit_uid')->remove();
    }

    return response()->json([
        'new_uid' => $newUid ?? null,
        'edit_uid' => $editUid ?? null
    ]);
});

Route::get('/', function () {
    return view('authentication.login');
});

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');
