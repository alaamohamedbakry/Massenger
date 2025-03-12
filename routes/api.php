<?php

use App\Http\Controllers\ConvegrationController;
use App\Http\Controllers\MassegesController;
use App\Http\Controllers\Api\UserAuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/user', function (Request $request) {
    return response()->json(['user' => $request->user()]);
})->middleware('auth:sanctum');

Route::post('user/register', [UserAuthController::class, 'register']);
Route::post('user/login', [UserAuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::get('convegrations',[ConvegrationController::class,'index']);
    Route::get('convegrations/{convegration}',[ConvegrationController::class,'show']);
    Route::post('convegrations/{convegration}/participents',[ConvegrationController::class,'addParticipants']);
    Route::delete('convegrations/{convegration}/participents',[ConvegrationController::class,'removeparticipants']);


    Route::get('convegrations/{id}/messages',[MassegesController::class,'index']);
    Route::post('messages',[MassegesController::class,'store'])
    ->name('api.masseges.store');
    Route::delete('messages/{id}',[MassegesController::class,'destroy']);



 });
