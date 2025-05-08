<?php

use App\Livewire\Admin\WatchStrapMaterial;
use Illuminate\Http\Request;
use App\Livewire\CustomLogin;
use App\Livewire\Admin\Watches;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Admin\AddWatchColor;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\WatchBrandlist;
use App\Livewire\Staff\StaffDashboard;
use App\Livewire\Admin\WatchCategorylist;
use App\Livewire\Admin\WatchDialColorlist;
use App\Livewire\Admin\WatchGlassTypeList;

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
    Route::get('admin/watch-list', Watches::class)->name('admin.watches')->middleware('role:admin');
    Route::get('admin/watch-color', AddWatchColor::class)->name('admin.watch-color')->middleware('role:admin');
    Route::get('admin/add-watch-brand', WatchBrandlist::class)->name('admin.watch-brand')->middleware('role:admin');
    Route::get('admin/watch-category', WatchCategorylist::class)->name('admin.watch-category')->middleware('role:admin');
    Route::get('admin/watch-dial-colorlist', WatchDialColorlist::class)->name('admin.watch-dial-color')->middleware('role:admin');
    Route::get('admin/watch-glass-type', WatchGlassTypeList::class)->name('admin.watch-glass-type')->middleware('role:admin');
    Route::get('admin/watch-strap-material', WatchStrapMaterial::class)->name('admin.watch-strap-material')->middleware('role:admin');
    
    // Staff routes
    Route::get('/staff/dashboard', StaffDashboard::class)->name('staff.dashboard')->middleware('role:staff');
    
    // Default dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
