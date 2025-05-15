<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\CustomLogin;
use App\Livewire\Admin\AdminDashboard;
use App\Livewire\Admin\Watches;
use App\Livewire\Admin\AddWatchColor;
use App\Livewire\Admin\WatchBrandlist;
use App\Livewire\Admin\WatchCategorylist;
use App\Livewire\Admin\WatchDialColorlist;
use App\Livewire\Admin\WatchGlassTypeList;
use App\Livewire\Admin\WatchStrapMaterial;
use App\Livewire\Admin\WatchTypes;
use App\Livewire\Admin\MadeByList;
use App\Livewire\Admin\SupplierList;
use App\Livewire\Admin\WatchStrapColorlist;
use App\Livewire\Admin\BillingPage;
use App\Livewire\Admin\ManageAdmin;
use App\Livewire\Admin\ManageStaff;
use App\Livewire\Admin\ManageCustomer;
use App\Livewire\Staff\StaffDashboard;
use App\Livewire\Staff\Billing;
use App\Http\Controllers\ReceiptController;

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

// Public routes
Route::get('/', CustomLogin::class)->name('welcome')->middleware('guest');

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
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/watch-list', Watches::class)->name('watches');
        Route::get('/watch-color', AddWatchColor::class)->name('watch-color');
        Route::get('/add-watch-brand', WatchBrandlist::class)->name('watch-brand');
        Route::get('/watch-category', WatchCategorylist::class)->name('watch-category');
        Route::get('/watch-dial-colorlist', WatchDialColorlist::class)->name('watch-dial-color');
        Route::get('/watch-glass-type', WatchGlassTypeList::class)->name('watch-glass-type');
        Route::get('/watch-strap-material', WatchStrapMaterial::class)->name('watch-strap-material');
        Route::get('/watch-types', WatchTypes::class)->name('watch-types');
        Route::get('/made-by-list', MadeByList::class)->name('made-by-list');
        Route::get('/supplier-list', SupplierList::class)->name('supplier-list');
        Route::get('/watch-strap-colorlist', WatchStrapColorlist::class)->name('watch-strap-color');
        Route::get('/billing-page', BillingPage::class)->name('billing-page');
        Route::get('/manage-admin', ManageAdmin::class)->name('manage-admin');
        Route::get('/manage-staff', ManageStaff::class)->name('manage-staff');
        Route::get('/manage-customer', ManageCustomer::class)->name('manage-customer');
    });

    // Receipt download (accessible to authenticated users)
    Route::get('/receipts/{id}/download', [ReceiptController::class, 'download'])->name('receipts.download');

    // Staff routes
    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', StaffDashboard::class)->name('dashboard');
        Route::get('/billing', Billing::class)->name('billing');
    });

});
