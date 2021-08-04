<?php

use App\Http\Controllers\HappeningController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/user/register', [UserController::class, 'register']);
Route::post('/user/login', [UserController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'show']);
    Route::get('/user/name', [UserController::class, 'getUserByNameOrEmail']);
    Route::post('/user/invitation', [InvitationController::class, 'createFromUser']);
    Route::put('/user/invitation/{id}', [InvitationController::class, 'manageTeamInvitation']);
    Route::get('/team/name', [TeamController::class, 'getTeamsByName']);
    Route::post('/team', [TeamController::class, 'create']);
});

Route::middleware(['auth:sanctum', 'isTeamMember'])->group(function () {
    Route::get('/team/{id}', [TeamController::class, 'show']);
    Route::delete('/user/team/{id}', [UserController::class, 'leaveTeam']);
});

Route::middleware(['auth:sanctum', 'isTeamAdmin'])->group(function () {
    Route::put('/team/{id}', [TeamController::class, 'update']);
    Route::put('/team/{id}/admin', [TeamController::class, 'manageAdmin']);
    Route::delete('/team/{id}/member', [TeamController::class, 'removeMember']);
    Route::delete('/team/{id}', [TeamController::class, 'delete']);
    Route::post('/team/{id}/invitation', [InvitationController::class, 'createFromTeam']);
    Route::put('/team/{id}/invitation', [InvitationController::class, 'manageUserInvitation']);
    Route::post('/team/{id}/happening', [HappeningController::class, 'create']);
});

Route::middleware(['auth:sanctum', 'IsHappeningMember'])->group(function () {
    Route::get('/happening/{id}', [HappeningController::class, 'show']);
});

Route::middleware(['auth:sanctum', 'isHappeningAdmin'])->group(function () {
    Route::put('/happening/{id}', [HappeningController::class, 'update']);
    Route::post('/happening/{id}/member', [HappeningController::class, 'addMember']);
    Route::put('/happening/{id}/member', [HappeningController::class, 'updatePresence']);
    Route::delete('/happening/{id}/member', [HappeningController::class, 'removeMember']);
    Route::delete('/happening/{id}', [HappeningController::class, 'delete']);
});
