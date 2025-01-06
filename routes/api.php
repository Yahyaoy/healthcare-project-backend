<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register/patient', [AuthController::class, 'registerPatient']);
Route::post('/register/doctor', [AuthController::class, 'registerDoctor']);
Route::post('/login',[AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/doctors', [PatientController::class, 'getDoctors']);
    Route::get('/doctor/{id}/schedule', [PatientController::class, 'getDoctorSchedule']);
    Route::post('/appointments/{doctor_id}', [PatientController::class, 'bookAppointment']);
    Route::get('/{doctor_id}/booked-slots', [PatientController::class, 'getBookedSlots']);
});
// to admin
Route::middleware(['auth:sanctum','isAdmin'])->group(function () {
    Route::get('admin/all-users', [AdminController::class, 'getAllUsers']);
    Route::post('/admin/verify-user/{username}', [AdminController::class, 'verifyUser']);
    Route::delete('/admin/delete-user/{username}', [AdminController::class,'deleteUser']);
    Route::get('/admin/pending-users',[AdminController::class,'pendingUsers']);
//    Route::post('/update-user/{username}', [AdminController::class, 'updateUser']);
});


Route::middleware(['auth:sanctum', 'isDoctor'])->group(function () {
    Route::post('/add-schedule', [DoctorController::class, 'addSchedule']);
    Route::get('/my-schedule', [DoctorController::class, 'getSchedule']);
    Route::get('/pending-patients', [DoctorController::class, 'getPendingPatients']);

})
;


