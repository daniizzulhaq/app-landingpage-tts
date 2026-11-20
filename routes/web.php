<?php

use App\Http\Controllers\Site;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Publik
|--------------------------------------------------------------------------
*/

Route::get('/', Site\HomeController::class)->name('home');
Route::get('sitemap.xml', [Site\SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('robots.txt', [Site\SitemapController::class, 'robots'])->name('robots');

Route::name('site.')->group(function () {
    // Profil
    Route::get('profil', [Site\ProfileController::class, 'profile'])->name('profile');
    Route::get('profil/pimpinan', [Site\ProfileController::class, 'leaders'])->name('leaders');
    Route::get('profil/struktur-organisasi', [Site\ProfileController::class, 'structure'])->name('structure');
    Route::get('satuan-kerja', [Site\ProfileController::class, 'units'])->name('units.index');
    Route::get('satuan-kerja/{unit:slug}', [Site\ProfileController::class, 'unit'])->name('units.show');
    Route::get('polsek', [Site\ProfileController::class, 'polsek'])->name('polsek');

    // Layanan
    Route::get('layanan', [Site\ServiceController::class, 'index'])->name('services.index');
    Route::get('layanan/{service:slug}', [Site\ServiceController::class, 'show'])->name('services.show');
    Route::get('pengaduan', [Site\ServiceController::class, 'complaints'])->name('complaints');
    Route::get('faq', [Site\ServiceController::class, 'faq'])->name('faq');

    // Publikasi
    Route::get('agenda', Site\AgendaController::class)->name('agenda');
    Route::get('{type}', [Site\PostController::class, 'index'])->whereIn('type', array_keys(Post::TYPES))->name('posts.index');
    Route::get('{type}/{post}', [Site\PostController::class, 'show'])->whereIn('type', array_keys(Post::TYPES))->name('posts.show');

    // Galeri
    Route::get('galeri/foto', [Site\GalleryController::class, 'photos'])->name('gallery.photos');
    Route::get('galeri/foto/{album}', [Site\GalleryController::class, 'album'])->name('gallery.album');
    Route::get('galeri/video', [Site\GalleryController::class, 'videos'])->name('gallery.videos');

    // Informasi publik
    Route::get('ppid', [Site\PpidController::class, 'index'])->name('ppid');
    Route::post('ppid/permohonan', [Site\PpidController::class, 'store'])->middleware('throttle:public-form')->name('ppid.store');
    Route::view('kontak', 'site.contact')->name('contact');

    Route::get('cari', Site\SearchController::class)->name('search');
});

require __DIR__.'/admin.php';
