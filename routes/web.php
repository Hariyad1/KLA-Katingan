<?php

/**
 * @method static string view()
 * @method static array compact()
 * @method static \Carbon\Carbon now()
 */

use App\Models\Media;
use App\Models\News;
use App\Models\Agenda;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\DynamicPageController;
use App\Http\Controllers\ContactUsController;
use App\Models\Kategori;
use App\Http\Controllers\Admin\SettingController;
use App\Models\Setting;
use App\Http\Controllers\WelcomeController;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory as WordIOFactory;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Dompdf\Dompdf;
use App\Http\Controllers\DokumenController;



require __DIR__ . '/auth.php';

Route::get('/', [WelcomeController::class, 'index'])->name('home');

/*
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
});
*/

// Single dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Media management untuk semua user yang sudah login
Route::middleware(['auth'])->group(function () {
    Route::prefix('manage/media')->group(function () {
        Route::get('/', function() {
            $media = Media::where(function($query) {
                $query->where('file', 'like', '%.jpg')
                      ->orWhere('file', 'like', '%.jpeg')
                      ->orWhere('file', 'like', '%.png')
                      ->orWhere('file', 'like', '%.gif');
            })->latest()->get();
            return view('admin.media.index', compact('media'));
        })->name('media.index');
        
        Route::get('/create', function () {
            return view('admin.media.create');
        })->name('media.create');
        
        Route::get('/{id}/edit', function ($id) {
            $media = Media::findOrFail($id);
            return view('admin.media.edit', compact('media'));
        })->name('media.edit');
    });
});

// Route untuk berita
Route::prefix('manage/berita')->group(function () {
    Route::get('/', function() {
        $news = News::with(['kategori', 'creator'])->latest()->get();
        return view('admin.berita.index', compact('news'));
    })->name('berita.index');
});

// Route untuk agenda yang bisa diakses semua user yang sudah login
Route::middleware(['auth'])->prefix('manage')->group(function () {
    Route::get('/agenda', function() {
        $agendas = Agenda::orderBy('tanggal', 'desc')->get();
        $expiredCount = Agenda::where('tanggal', '<', now()->subDay())->count();
        return view('admin.agenda.index', compact('agendas', 'expiredCount'));
    })->name('admin.agenda.index');
});

// Route untuk kategori berita
Route::middleware(['auth', 'admin'])->prefix('manage')->group(function () {
    Route::prefix('kategori')->group(function () {
        Route::get('/', function() {
            $categories = Kategori::withCount('news')->latest()->get();
            return view('admin.kategori.index', compact('categories'));
        })->name('admin.kategori.index');

        Route::get('/create', function () {
            return view('admin.kategori.create');
        })->name('admin.kategori.create');
        
        Route::get('/{id}/edit', function ($id) {
            $kategori = Kategori::findOrFail($id);
            return view('admin.kategori.edit', compact('kategori'));
        })->name('admin.kategori.edit');
    });
});

// Route untuk dokumen admin
Route::prefix('manage/dokumen')->group(function () {
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

    Route::get('/create', function() {
        return view('admin.dokumen.create');
    })->name('admin.dokumen.create');

    Route::get('/{id}/edit', function($id) {
        $document = Media::findOrFail($id);
        return view('admin.dokumen.edit', compact('document'));
    })->name('admin.dokumen.edit');
});

// Route untuk kontak admin
Route::get('/manage/kontak', function() {
    $contacts = \App\Models\Contact::latest()->get();
    return view('admin.kontak.index', compact('contacts'));
})->name('admin.kontak.index');

// Route untuk setting - hanya bisa diakses admin
Route::middleware(['auth', 'admin'])->prefix('manage/setting')->group(function () {
    // Setting Statis
    Route::get('/statis', [SettingController::class, 'indexStatis'])->name('admin.setting.statis.index');
    Route::get('/statis/create', [SettingController::class, 'createStatis'])->name('admin.setting.statis.create');
    Route::get('/statis/edit/{setting}', [SettingController::class, 'editStatis'])->name('admin.setting.statis.edit');
    
    // Setting Video
    Route::get('/video', [SettingController::class, 'indexVideo'])->name('admin.setting.video.index'); 
    Route::get('/video/create', [SettingController::class, 'createVideo'])->name('admin.setting.video.create');
    Route::get('/video/edit/{setting}', [SettingController::class, 'editVideo'])->name('admin.setting.video.edit');
});

// Route untuk user management - hanya bisa diakses admin
Route::prefix('manage/users')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('admin.users.create');
    Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('admin.users.edit');
});

Route::get('/video', function () {
    $videoSettings = Setting::where('type', 'video')
                          ->latest()
                          ->paginate(6);
    return view('beranda.video', compact('videoSettings'));
})->name('video');

Route::get('/video/halaman/{page}', function ($page) {
    $videoSettings = Setting::where('type', 'video')
                          ->latest()
                          ->paginate(6, ['*'], 'page', $page);
    return view('beranda.video', compact('videoSettings'));
})->name('video.page');

Route::get('/galeri', function () {
    $media = Media::where(function($query) {
        $query->where('file', 'like', '%.jpg')
              ->orWhere('file', 'like', '%.jpeg')
              ->orWhere('file', 'like', '%.png')
              ->orWhere('file', 'like', '%.gif');
    })->latest()->get();
    return view('beranda.galeri', compact('media'));
})->name('galeri');

Route::get('/dokumen', function (Request $request) {
    $query = Media::where(function($query) {
        $query->where('file', 'like', '%.pdf')
              ->orWhere('file', 'like', '%.doc')
              ->orWhere('file', 'like', '%.docx')
              ->orWhere('file', 'like', '%.xls')
              ->orWhere('file', 'like', '%.xlsx');
    });
    
    // Handle search dengan escape string untuk mencegah SQL injection
    if ($request->filled('q')) {
        $search = strip_tags($request->q);
        $query->where('name', 'like', '%' . addslashes($search) . '%');
    }
    
    // Validasi per_page hanya boleh angka tertentu
    $allowedPerPage = [10, 25, 50, 100];
    $perPage = in_array($request->input('show'), $allowedPerPage) ? $request->input('show') : 10;
    
    $media = $query->latest()->paginate($perPage);
    
    if ($request->ajax()) {
        return view('beranda.dokumen-list', compact('media'))->render();
    }
    
    return view('beranda.dokumen', compact('media'));
})->name('dokumen');

Route::get('/dokumen/preview/{id}', [DokumenController::class, 'preview'])->name('dokumen.preview');

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
    // Route::get('/', function () {
    //     return view('pemenuhan-hak-anak.index');
    // })->name('pemenuhan-hak-anak');
    
    Route::get('/klaster-1', function() {
        // Cari konten dinamis dengan URL yang sama
        $settings = Setting::where('type', 'statis')
                          ->where(function($query) {
                              $query->where('url', 'pemenuhan-hak-anak/klaster-1')
                                    ->orWhere('url', 'pemenuhan_hak_anak/klaster_1');
                          })
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Gabungkan dengan view statis
        return view('pemenuhan-hak-anak.klaster1', [
            'settings' => $settings,
            'hasAdditionalContent' => $settings->isNotEmpty()
        ]);
    })->name('pemenuhan-hak-anak.klaster1');
    
    Route::get('/klaster-2', function () {
        // Cari konten dinamis dengan URL yang sama
        $settings = Setting::where('type', 'statis')
                          ->where(function($query) {
                              $query->where('url', 'pemenuhan-hak-anak/klaster-2')
                                    ->orWhere('url', 'pemenuhan_hak_anak/klaster_2');
                          })
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Gabungkan dengan view statis
        return view('pemenuhan-hak-anak.klaster2', [
            'settings' => $settings,
            'hasAdditionalContent' => $settings->isNotEmpty()
        ]);
    })->name('pemenuhan-hak-anak.klaster2');
    
    Route::get('/klaster-3', function () {
        // Cari konten dinamis dengan URL yang sama
        $settings = Setting::where('type', 'statis')
                          ->where(function($query) {
                              $query->where('url', 'pemenuhan-hak-anak/klaster-3')
                                    ->orWhere('url', 'pemenuhan_hak_anak/klaster_3');
                          })
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Gabungkan dengan view statis
        return view('pemenuhan-hak-anak.klaster3', [
            'settings' => $settings,
            'hasAdditionalContent' => $settings->isNotEmpty()
        ]);
    })->name('pemenuhan-hak-anak.klaster3');
    
    Route::get('/klaster-4', function () {
        // Cari konten dinamis dengan URL yang sama
        $settings = Setting::where('type', 'statis')
                          ->where(function($query) {
                              $query->where('url', 'pemenuhan-hak-anak/klaster-4')
                                    ->orWhere('url', 'pemenuhan_hak_anak/klaster_4');
                          })
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Gabungkan dengan view statis
        return view('pemenuhan-hak-anak.klaster4', [
            'settings' => $settings,
            'hasAdditionalContent' => $settings->isNotEmpty()
        ]);
    })->name('pemenuhan-hak-anak.klaster4');
});

Route::get('/kontak', [ContactUsController::class, 'index'])->name('kontak');
Route::post('/kontak', [ContactUsController::class, 'store']);

Route::prefix('perlindungan-khusus-anak')->group(function () {
    Route::get('/klaster-5', function () {
        // Cari konten dinamis dengan URL yang sama
        $settings = Setting::where('type', 'statis')
                          ->where(function($query) {
                              $query->where('url', 'perlindungan-khusus-anak/klaster-5')
                                    ->orWhere('url', 'perlindungan_khusus_anak/klaster_5');
                          })
                          ->orderBy('created_at', 'asc')
                          ->get();

        // Gabungkan dengan view statis
        return view('perlindungan-khusus-anak.klaster5', [
            'settings' => $settings,
            'hasAdditionalContent' => $settings->isNotEmpty()
        ]);
    })->name('perlindungan-khusus-anak.klaster5');
});

Route::get('/berita', function () {
    $query = News::with(['kategori', 'creator'])
                ->where('status', 1)
                ->latest();
    
    $news = $query->paginate(6);
    $categories = Kategori::withCount('news')->get();
    
    return view('beranda.berita', compact('news', 'categories'));
})->name('berita');

Route::get('/berita/kategori/{kategori}', function ($kategori) {
    $query = News::with(['kategori', 'creator'])
                ->where('status', 1)
                ->whereHas('kategori', function($q) use ($kategori) {
                    $q->where('name', 'like', str_replace('-', ' ', $kategori));
                })
                ->latest();
    
    $news = $query->paginate(6);
    $categories = Kategori::withCount('news')->get();
    
    return view('beranda.berita', compact('news', 'categories', 'kategori'));
})->name('berita.kategori');

Route::get('/berita/kategori/{kategori}/halaman/{page}', function ($kategori, $page) {
    $query = News::with(['kategori', 'creator'])
                ->where('status', 1)
                ->whereHas('kategori', function($q) use ($kategori) {
                    $q->where('name', 'like', str_replace('-', ' ', $kategori));
                })
                ->latest();
    
    $news = $query->paginate(3, ['*'], 'page', $page);
    $categories = Kategori::withCount('news')->get();
    
    return view('beranda.berita', compact('news', 'categories', 'kategori'));
})->name('berita.kategori.page');

Route::get('/berita/halaman/{page}', function ($page) {
    $query = News::with(['kategori', 'creator'])
                ->where('status', 1)
                ->latest();
    
    $news = $query->paginate(3, ['*'], 'page', $page);
    $categories = Kategori::withCount('news')->get();
    
    return view('beranda.berita', compact('news', 'categories'));
})->name('berita.page');

Route::get('/berita/baca/{title}', [NewsController::class, 'show'])->name('news.show');

// Route untuk admin - hanya bisa diakses oleh admin
Route::middleware(['auth', 'admin'])->prefix('manage')->name('admin.')->group(function () {
    // User management
    Route::prefix('users')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    });
    
    // Setting management
    Route::prefix('setting')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/create', [SettingController::class, 'create'])->name('setting.create');
        Route::get('/edit/{setting}', [SettingController::class, 'edit'])->name('setting.edit');
    });

    // Agenda management (create, edit, delete)
    Route::prefix('agenda')->group(function () {
        Route::get('/create', function () {
            return view('admin.agenda.create');
        })->name('agenda.create');
        
        Route::get('/{id}/edit', function ($id) {
            return view('admin.agenda.edit', compact('id'));
        })->name('agenda.edit');
    });

    // Berita management
    Route::prefix('news')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\NewsController::class, 'index'])->name('news.index');
        Route::get('/create', [App\Http\Controllers\Admin\NewsController::class, 'create'])->name('news.create');
        Route::get('/{news}/edit', [App\Http\Controllers\Admin\NewsController::class, 'edit'])->name('news.edit');
    });

    // Kategori management
    Route::prefix('kategori')->group(function () {
        Route::get('/', function() {
            $categories = Kategori::withCount('news')->latest()->get();
            return view('admin.kategori.index', compact('categories'));
        })->name('kategori.index');
        
        Route::get('/create', function () {
            return view('admin.kategori.create');
        })->name('kategori.create');
        
        Route::get('/{id}/edit', function ($id) {
            $kategori = Kategori::findOrFail($id);
            return view('admin.kategori.edit', compact('kategori'));
        })->name('kategori.edit');
    });

    // Klaster Routes
    Route::resource('klaster', App\Http\Controllers\Admin\KlasterController::class);
    Route::resource('indikator', App\Http\Controllers\Admin\IndikatorController::class);
});

Route::prefix('galeri')->group(function () {
    // Halaman index galeri
    Route::get('/', function () {
        $media = Media::where(function($query) {
            $query->where('file', 'like', '%.jpg')
                  ->orWhere('file', 'like', '%.jpeg')
                  ->orWhere('file', 'like', '%.png')
                  ->orWhere('file', 'like', '%.gif');
        })->latest()->paginate(12);
        return view('beranda.galeri', compact('media'));
    })->name('galeri');
    
    // Detail galeri
    Route::get('/{id}', function ($id) {
        $media = Media::findOrFail($id);
        // Increment hits counter
        $media->increment('hits');
        return view('beranda.galeri-detail', compact('media'));
    })->name('gallery.show');
});

Route::get('/berita/{kategori?}', function ($kategori = null) {
    $query = News::with(['kategori', 'creator'])
                ->where('status', 1)
                ->latest();
    
    if ($kategori) {
        $query->whereHas('kategori', function($q) use ($kategori) {
            $q->where('name', 'like', str_replace('-', ' ', $kategori));
        });
    }
    
    $news = $query->paginate(3);
    if ($kategori) {
        $news->appends(['kategori' => $kategori]);
    }
    
    $categories = Kategori::withCount('news')->get();
    
    return view('beranda.berita', compact('news', 'categories', 'kategori'));
})->name('berita');

// Tambahkan route untuk upload gambar
Route::post('/upload-image', [App\Http\Controllers\ImageUploadController::class, 'upload'])->name('upload.image');

// Rute untuk menghapus berita
Route::delete('/admin/news/{id}', [NewsController::class, 'destroy'])->name('admin.news.destroy');

// Route untuk user berita
Route::middleware(['auth'])->prefix('my')->name('user.')->group(function () {
    Route::prefix('news')->group(function () {
        Route::get('/', [App\Http\Controllers\User\NewsController::class, 'index'])->name('news.index');
        Route::get('/create', [App\Http\Controllers\User\NewsController::class, 'create'])->name('news.create');
        Route::get('/{news}/edit', [App\Http\Controllers\User\NewsController::class, 'edit'])->name('news.edit');
    });
});

// Route untuk OPD
Route::middleware(['auth', 'admin'])->prefix('manage/opd')->name('admin.opd.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\OpdController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\OpdController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\OpdController::class, 'store'])->name('store');
    Route::get('/{opd}/edit', [App\Http\Controllers\Admin\OpdController::class, 'edit'])->name('edit');
    Route::put('/{opd}', [App\Http\Controllers\Admin\OpdController::class, 'update'])->name('update');
    Route::delete('/{opd}', [App\Http\Controllers\Admin\OpdController::class, 'destroy'])->name('destroy');
});

// Route untuk Data Dukung (Admin)
Route::middleware(['auth', 'admin'])->prefix('manage/data-dukung')->name('admin.data-dukung.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DataDukungController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\DataDukungController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\DataDukungController::class, 'store'])->name('store');
    Route::get('/{dataDukung}/edit', [App\Http\Controllers\Admin\DataDukungController::class, 'edit'])->name('edit');
    Route::put('/{dataDukung}', [App\Http\Controllers\Admin\DataDukungController::class, 'update'])->name('update');
    Route::delete('/{dataDukung}', [App\Http\Controllers\Admin\DataDukungController::class, 'destroy'])->name('destroy');
    Route::post('/{dataDukung}/approve', [App\Http\Controllers\Admin\DataDukungController::class, 'approve'])->name('approve');
    Route::post('/{dataDukung}/reject', [App\Http\Controllers\Admin\DataDukungController::class, 'reject'])->name('reject');
    Route::delete('/file/{file}', [App\Http\Controllers\Admin\DataDukungController::class, 'destroyFile'])->name('destroy-file');
});

// Route untuk Data Dukung (User)
Route::middleware(['auth'])->prefix('user/data-dukung')->name('user.data-dukung.')->group(function () {
    Route::get('/', [App\Http\Controllers\User\DataDukungController::class, 'index'])->name('index');
    Route::get('/list', [App\Http\Controllers\User\DataDukungController::class, 'list'])->name('list');
    Route::get('/create', [App\Http\Controllers\User\DataDukungController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\User\DataDukungController::class, 'store'])->name('store');
    Route::get('/{dataDukung}/edit', [App\Http\Controllers\User\DataDukungController::class, 'edit'])->name('edit');
    Route::put('/{dataDukung}', [App\Http\Controllers\User\DataDukungController::class, 'update'])->name('update');
    Route::delete('/{dataDukung}', [App\Http\Controllers\User\DataDukungController::class, 'destroy'])->name('destroy');
    Route::delete('/file/{file}', [App\Http\Controllers\User\DataDukungController::class, 'destroyFile'])->name('destroy-file');
});

Route::get('/{url}', [DynamicPageController::class, 'show'])
    ->where('url', '.*')
    ->name('dynamic.page');
