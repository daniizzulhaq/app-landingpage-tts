<?php

use App\Http\Controllers\Admin;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Panel Admin (/admin)
|--------------------------------------------------------------------------
| Super Admin  : semua menu.
| Admin Unit   : konten unitnya sendiri (dibatasi Policy di setiap aksi).
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('login', [Admin\AuthController::class, 'create'])->name('login');
        Route::post('login', [Admin\AuthController::class, 'store'])->middleware('throttle:login')->name('login.store');
    });

    Route::middleware(['auth', 'active'])->group(function () {
        Route::post('logout', [Admin\AuthController::class, 'destroy'])->name('logout');
        Route::get('/', Admin\DashboardController::class)->name('dashboard');
        Route::get('password', [Admin\PasswordController::class, 'edit'])->name('password.edit');
        Route::put('password', [Admin\PasswordController::class, 'update'])->name('password.update');

        // ---- Bisa diakses Admin Unit (dibatasi Policy) ----
        Route::resource('units', Admin\UnitController::class)->except('show');

        Route::prefix('konten/{type}')->name('posts.')->whereIn('type', array_keys(Post::TYPES))->group(function () {
            Route::get('/', [Admin\PostController::class, 'index'])->name('index');
            Route::get('create', [Admin\PostController::class, 'create'])->name('create');
            Route::post('/', [Admin\PostController::class, 'store'])->name('store');
            Route::get('{post}', [Admin\PostController::class, 'show'])->whereNumber('post')->name('show');
            Route::get('{post}/edit', [Admin\PostController::class, 'edit'])->whereNumber('post')->name('edit');
            Route::put('{post}', [Admin\PostController::class, 'update'])->whereNumber('post')->name('update');
            Route::delete('{post}', [Admin\PostController::class, 'destroy'])->whereNumber('post')->name('destroy');
        });

        Route::resource('albums', Admin\AlbumController::class)->except('show');
        Route::delete('albums/{album}/photos/{photo}', [Admin\AlbumController::class, 'destroyPhoto'])->name('albums.photos.destroy');
        Route::resource('agendas', Admin\AgendaController::class)->except('show');

        // ---- Khusus Super Admin ----
        Route::middleware('role:super-admin')->group(function () {
            Route::get('approval', [Admin\ApprovalController::class, 'index'])->name('approval.index');
            Route::post('approval/posts/{post}/approve', [Admin\ApprovalController::class, 'approvePost'])->name('approval.posts.approve');
            Route::post('approval/posts/{post}/reject', [Admin\ApprovalController::class, 'rejectPost'])->name('approval.posts.reject');
            Route::post('approval/albums/{album}/approve', [Admin\ApprovalController::class, 'approveAlbum'])->name('approval.albums.approve');
            Route::post('approval/albums/{album}/reject', [Admin\ApprovalController::class, 'rejectAlbum'])->name('approval.albums.reject');
            Route::post('approval/agendas/{agenda}/approve', [Admin\ApprovalController::class, 'approveAgenda'])->name('approval.agendas.approve');
            Route::post('approval/agendas/{agenda}/reject', [Admin\ApprovalController::class, 'rejectAgenda'])->name('approval.agendas.reject');

            Route::resource('categories', Admin\CategoryController::class)->except('show');
            Route::resource('officials', Admin\OfficialController::class)->except('show');
            Route::resource('users', Admin\UserController::class)->except('show');

            Route::get('pages', [Admin\PageController::class, 'index'])->name('pages.index');
            Route::get('pages/{page}/edit', [Admin\PageController::class, 'edit'])->name('pages.edit');
            Route::put('pages/{page}', [Admin\PageController::class, 'update'])->name('pages.update');
            Route::resource('sliders', Admin\SliderController::class)->except('show');
            Route::resource('faqs', Admin\FaqController::class)->except('show');
            Route::resource('complaint-channels', Admin\ComplaintChannelController::class)->except('show');
            Route::resource('videos', Admin\VideoController::class)->except('show');
            Route::resource('services', Admin\ServiceController::class)->except('show');
            Route::resource('documents', Admin\DocumentController::class)->except('show');
            Route::resource('information-requests', Admin\InformationRequestController::class)->only(['index', 'show', 'update', 'destroy']);
            Route::get('settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
            Route::put('settings', [Admin\SettingController::class, 'update'])->name('settings.update');
        });
    });
});
