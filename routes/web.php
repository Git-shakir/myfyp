<?php
//myproject\routes\web.php
use Kreait\Firebase\Database;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Firebase\AuthController;
use App\Http\Controllers\Firebase\animalDataController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('loginpage');
Route::post('/login', [AuthController::class, 'login'])->name('firebase.login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('registerpage');
Route::post('/register', [AuthController::class, 'register'])->name('firebase.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('animalsData', [animalDataController::class, 'index'])->name('list-animalData');
Route::get('add-animalData', [animalDataController::class, 'create'])->name('add-animalData');
Route::post('add-animalData', [animalDataController::class, 'store'])->name('add-animalData-post');
Route::get('edit-animalData/{animalKey}', [animalDataController::class, 'edit'])->name('edit-animalData');
Route::put('update-animalData/{animalKey}', [animalDataController::class, 'update'])->name('update-animalData');
Route::delete('delete-animalData/{animalKey}', [animalDataController::class, 'destroy']);
Route::get('rfid-Logs', [animalDataController::class, 'getUid']);
Route::get('/activity-logs', [animalDataController::class, 'showActivityLogs']);
Route::get('/get-livestock-details/{animalId}', [animalDataController::class, 'getLivestockDetails']);
Route::get('/activity-logs', [animalDataController::class, 'showActivityLogs'])->name('activity.logs');
Route::get('get-animal-history/{animalId}', [animalDataController::class, 'getAnimalHistory']);

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

Route::get('/settings', function () {
    return view('settings');
})->name('settings');

Route::get('/reports', function () {
    return view('reports');
})->name('reports');

