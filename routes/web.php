<?php

use App\Models\Media;
use App\Models\News;
use App\Models\Agenda;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DynamicPageController;
use App\Http\Controllers\ContactUsController;
use App\Models\Kategori;
use Illuminate\Http\Request;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    $slides = Media::where('slide_show', true)->get();
    $latestNews = News::with(['kategori', 'creator'])
                    ->latest()
                    ->take(5)
                    ->get();
    $agenda = Agenda::latest()->get();
    return view('welcome', compact('slides', 'latestNews', 'agenda'));
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Single dashboard route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Tambahkan route media (khusus gambar)
    Route::get('/media', function() {
        $media = Media::where(function($query) {
            $query->where('file', 'like', '%.jpg')
                  ->orWhere('file', 'like', '%.jpeg')
                  ->orWhere('file', 'like', '%.png')
                  ->orWhere('file', 'like', '%.gif');
        })->latest()->get();
        return view('admin.media.index', compact('media'));
    })->name('media.index');

    // Route untuk berita
    Route::prefix('berita')->group(function () {
        Route::get('/', function() {
            $news = News::with(['kategori', 'creator'])->latest()->get();
            return view('admin.berita.index', compact('news'));
        })->name('berita.index');
    });

    // Route untuk agenda
    Route::prefix('agenda')->group(function () {
        Route::get('/', function() {
            $agendas = Agenda::latest()->get();
            return view('admin.agenda.index', compact('agendas'));
        })->name('agenda.index');
    });

    // Route untuk kategori berita
    Route::prefix('kategori')->group(function () {
        Route::get('/', function() {
            $categories = Kategori::withCount('news')->latest()->get();
            return view('admin.kategori.index', compact('categories'));
        })->name('kategori.index');
    });

    // Route untuk dokumen admin
    Route::prefix('admin/dokumen')->group(function () {
        Route::get('/', function() {
            $documents = Media::where(function($query) {
                $query->where('file', 'like', '%.pdf')
                      ->orWhere('file', 'like', '%.doc')
                      ->orWhere('file', 'like', '%.docx')
                      ->orWhere('file', 'like', '%.xls')
                      ->orWhere('file', 'like', '%.xlsx');
            })->latest()->get();
            return view('admin.dokumen.index', compact('documents'));
        })->name('admin.dokumen.index');
    });

    // Route untuk kontak admin
    Route::get('/admin/kontak', function() {
        $contacts = \App\Models\Contact::latest()->get();
        return view('admin.kontak.index', compact('contacts'));
    })->name('admin.kontak.index');

    // Route untuk setting
    Route::prefix('admin/setting')->group(function () {
        Route::get('/', function() {
            $settings = \App\Models\Setting::latest()->get();
            return view('admin.setting.index', compact('settings'));
        })->name('admin.setting.index');
        
        Route::get('/{id}/edit', function($id) {
            $setting = \App\Models\Setting::findOrFail($id);
            return view('admin.setting.edit', compact('setting'));
        })->name('admin.setting.edit');
    });
});

Route::get('/galeri', function () {
    $media = Media::where(function($query) {
        $query->where('file', 'like', '%.jpg')
              ->orWhere('file', 'like', '%.jpeg')
              ->orWhere('file', 'like', '%.png')
              ->orWhere('file', 'like', '%.gif');
    })->latest()->get();
    return view('beranda.galeri', compact('media'));
})->name('galeri');

Route::get('/dokumen', function () {
    $media = Media::where(function($query) {
        $query->where('file', 'like', '%.pdf')
              ->orWhere('file', 'like', '%.doc')
              ->orWhere('file', 'like', '%.docx')
              ->orWhere('file', 'like', '%.xls')
              ->orWhere('file', 'like', '%.xlsx');
    })->latest()->get();
    return view('beranda.dokumen', compact('media'));
})->name('dokumen');

Route::prefix('profil')->group(function () {
    Route::get('/', function () {
        return view('profil.index');
    })->name('profil');
    
    Route::get('/struktur-organisasi', function () {
        return view('profil.struktur');
    })->name('profil.struktur');
    
    Route::get('/visi-misi', function () {
        return view('profil.visi-misi');
    })->name('profil.visi-misi');
    
    Route::get('/program-kerja', function () {
        return view('profil.program');
    })->name('profil.program');
});

Route::prefix('pemenuhan-hak-anak')->group(function () {
    Route::get('/', function () {
        return view('pemenuhan-hak-anak.index');
    })->name('pemenuhan-hak-anak');
    
    Route::get('/klaster-1', function () {
        return view('pemenuhan-hak-anak.klaster1');
    })->name('pemenuhan-hak-anak.klaster1');
    
    Route::get('/klaster-2', function () {
        return view('pemenuhan-hak-anak.klaster2');
    })->name('pemenuhan-hak-anak.klaster2');
    
    Route::get('/klaster-3', function () {
        return view('pemenuhan-hak-anak.klaster3');
    })->name('pemenuhan-hak-anak.klaster3');
    
    Route::get('/klaster-4', function () {
        return view('pemenuhan-hak-anak.klaster4');
    })->name('pemenuhan-hak-anak.klaster4');
});

Route::get('/kontak', [ContactUsController::class, 'index'])->name('kontak');
Route::post('/kontak', [ContactUsController::class, 'store']);

Route::prefix('perlindungan-khusus-anak')->group(function () {
    Route::get('/', function () {
        return view('perlindungan-khusus-anak.index');
    })->name('perlindungan-khusus-anak');
    
    Route::get('/klaster-5', function () {
        return view('perlindungan-khusus-anak.klaster5');
    })->name('perlindungan-khusus-anak.klaster5');
});

Route::get('/berita/baca/{id}/{slug}', [NewsController::class, 'show'])->name('news.show');

require __DIR__ . '/auth.php';

Route::get('/{url}', [DynamicPageController::class, 'show'])->name('dynamic.page');