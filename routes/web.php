<?php

use App\Http\Controllers\CommunityRequestController;
use App\Http\Controllers\CommunityJoinController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');
});

    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    // Dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::get('/dashboard', function () {
        $user = auth()->user();

        $ledCommunity = null;

        if ($user->role === 'ketua') {
            $ledCommunity = \App\Models\Community::where(
                'leader_id',
                $user->id
            )->first();
        }

    return view('dashboard', compact('ledCommunity'));
    })->middleware('auth')->name('dashboard');

// Member: melihat dan mengajukan bergabung ke komunitas
    Route::middleware('auth')->group(function () {
    Route::get('/communities', [CommunityJoinController::class, 'index'])
        ->name('communities.index');

    Route::post('/communities/{community}/join', [CommunityJoinController::class, 'store'])
        ->name('communities.join');
});

// Ketua: mengelola permintaan bergabung
Route::middleware('auth')
    ->prefix('ketua')
    ->name('ketua.')
    ->group(function () {

        Route::get('/communities/{community}/requests',
            [CommunityRequestController::class, 'index'])
            ->name('communities.requests.index');

        Route::patch('/communities/{community}/requests/{membership}/approve',
            [CommunityRequestController::class, 'approve'])
            ->name('communities.requests.approve');

        Route::patch('/communities/{community}/requests/{membership}/reject',
            [CommunityRequestController::class, 'reject'])
            ->name('communities.requests.reject');
    });

// Admin
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/communities', [CommunityController::class, 'index'])
            ->name('communities.index');

        Route::get('/communities/create', [CommunityController::class, 'create'])
            ->name('communities.create');

        Route::post('/communities', [CommunityController::class, 'store'])
            ->name('communities.store');

        Route::get('/communities/{community}/members', [MemberController::class, 'index'])
            ->name('communities.members.index');

        Route::get('/communities/{community}/members/create', [MemberController::class, 'create'])
            ->name('communities.members.create');

        Route::post('/communities/{community}/members', [MemberController::class, 'store'])
            ->name('communities.members.store');

        Route::delete('/communities/{community}/members/{membership}', [MemberController::class, 'destroy'])
            ->name('communities.members.destroy');

        Route::put('/communities/{community}/leader', [CommunityController::class, 'updateLeader'])
            ->name('communities.leader.update');
    });