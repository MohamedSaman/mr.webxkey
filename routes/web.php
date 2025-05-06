<?php

use App\Livewire\Admin\AdminDashboard;
use App\Livewire\CustomLogin;
use App\Livewire\Staff\StaffDashboard;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Use the custom login component for the root route
Route::get("/", CustomLogin::class)->name("welcome")->middleware('guest');

// Custom logout route
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Routes that require authentication
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Admin routes
    Route::get('/admin/dashboard', AdminDashboard::class)->name('admin.dashboard')->middleware('role:admin');
    
    // Staff routes
    Route::get('/staff/dashboard', StaffDashboard::class)->name('staff.dashboard')->middleware('role:staff');
    
    // Default dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
