<?php
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\UsersController;
use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\postsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\ticketsController;
// use Notification;
use App\Notifications\ticketNotification;
use Illuminate\Support\Facades\Route;
use Spatie\SiteSearch\Search;
use App\Models\flight;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('dashboard', function () {
    $count_notifications = auth()->user()->unreadNotifications->count();
    // dd($count_notifications);
    return view('dashboard',compact('count_notifications'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/search', [UsersController::class,'search'])->name('search');
Route::post('/search', [RolesController::class,'search'])->name('search');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// admin routes
Route::group(['prefix' => 'admin'], function () {
    // Route::get('/', 'Backend\DashboardController@index')->name('admin.dashboard');
    Route::resource('roles', RolesController::class, ['names' => 'admin.roles']);
    Route::resource('users', UsersController::class, ['names' => 'admin.users']);
    Route::resource('admins', AdminsController::class, ['names' => 'admin.admins']);
});
Route::get('/tickets', [ticketsController::class,'index'])->name('tickets.index');
Route::get('/tickets/create', [ticketsController::class,'create'])->name('admin.tickets.create');
Route::post('/tickets/store', [ticketsController::class,'store'])->name('admin.tickets.store');
Route::get('/tickets/edit/{id}', [ticketsController::class,'edit'])->name('admin.tickets.edit');
Route::post('/tickets/update', [ticketsController::class,'update'])->name('admin.tickets.update');
Route::get('/tickets/destroy/{id}', [ticketsController::class,'destroy'])->name('ticket.destroy');
Route::get('department/users/{id}', [ticketsController::class,'getDepartmentUsers'])->name('dapartment.users');
Route::get('/ticket/assign/{id}', [ticketsController::class,'assign'])->name('ticket.assign');
Route::post('/ticket/assignTo', [ticketsController::class,'assignTo'])->name('ticket.assignTo');
Route::post('/tickets/comment', [ticketsController::class,'comment'])->name('ticket.comment');
Route::get('/tickets/info/{id}', [ticketsController::class,'getInfo'])->name('ticket.info');

Route::get('/sendNotification', [ticketsController::class,'sendTicketNotification']);
Route::get('/readNotification', [ticketsController::class,'markAsRead'])->name('notifications.read');
Route::get('/notifications/viewAll', [ticketsController::class,'viewAllNotifications'])->name('notifications.viewAll');

require __DIR__.'/auth.php';