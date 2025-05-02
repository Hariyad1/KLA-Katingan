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
use App\Http\Controllers\KlasterController;
use App\Http\Controllers\User\DataDukungController;
use App\Http\Controllers\Admin\DataDukungController as AdminDataDukungController;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

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

Route::middleware(['auth', 'admin'])->prefix('manage/berita')->group(function () {
    Route::get('/', function() {
        $news = News::with(['kategori', 'creator'])->latest()->get();
        return view('admin.berita.index', compact('news'));
    })->name('berita.index');
});

Route::middleware(['auth'])->prefix('manage')->group(function () {
    Route::get('/agenda', function() {
        $agendas = Agenda::orderBy('tanggal', 'desc')->get();
        $expiredCount = Agenda::where('tanggal', '<', now()->subDay())->count();
        return view('admin.agenda.index', compact('agendas', 'expiredCount'));
    })->name('admin.agenda.index');
    
    Route::prefix('agenda')->group(function () {
        Route::get('/create', function () {
            return view('admin.agenda.create');
        })->name('admin.agenda.create');
        
        Route::get('/{id}/edit', function ($id) {
            return view('admin.agenda.edit', compact('id'));
        })->name('admin.agenda.edit');
    });
});

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

Route::middleware(['auth'])->prefix('manage/dokumen')->group(function () {
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

Route::get('/manage/kontak', function() {
    $contacts = \App\Models\Contact::latest()->get();
    return view('admin.kontak.index', compact('contacts'));
})->name('admin.kontak.index');

Route::middleware(['auth', 'admin'])->prefix('manage/setting')->group(function () {
    Route::get('/statis', [SettingController::class, 'indexStatis'])->name('admin.setting.statis.index');
    Route::get('/statis/create', [SettingController::class, 'createStatis'])->name('admin.setting.statis.create');
    Route::get('/statis/edit/{setting}', [SettingController::class, 'editStatis'])->name('admin.setting.statis.edit');
    
    Route::get('/video', [SettingController::class, 'indexVideo'])->name('admin.setting.video.index'); 
    Route::get('/video/create', [SettingController::class, 'createVideo'])->name('admin.setting.video.create');
    Route::get('/video/edit/{setting}', [SettingController::class, 'editVideo'])->name('admin.setting.video.edit');
});

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
    
    if ($request->filled('q')) {
        $search = strip_tags($request->q);
        $query->where('name', 'like', '%' . addslashes($search) . '%');
    }
    
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

Route::get('/profil/program-kerja', [App\Http\Controllers\ProfilController::class, 'program'])->name('profil.program');
Route::get('/profil/program-kerja/tambah', [App\Http\Controllers\ProfilController::class, 'create'])->name('profil.program.create');
Route::post('/profil/program-kerja', [App\Http\Controllers\ProfilController::class, 'store'])->name('profil.program.store');
Route::get('/profil/program-kerja/{id}/edit', [App\Http\Controllers\ProfilController::class, 'edit'])->name('profil.program.edit');
Route::put('/profil/program-kerja/{id}', [App\Http\Controllers\ProfilController::class, 'update'])->name('profil.program.update');
Route::delete('/profil/program-kerja/{id}', [App\Http\Controllers\ProfilController::class, 'destroy'])->name('profil.program.destroy');

Route::prefix('pemenuhan-hak-anak')->group(function () {
    // Route::get('/', function () {
    //     return view('pemenuhan-hak-anak.index');
    // })->name('pemenuhan-hak-anak');
    
    Route::get('/klaster-1', [KlasterController::class, 'klaster1'])->name('pemenuhan-hak-anak.klaster1');
    
    Route::get('/klaster-2', [KlasterController::class, 'klaster2'])->name('pemenuhan-hak-anak.klaster2');
    
    Route::get('/klaster-3', [KlasterController::class, 'klaster3'])->name('pemenuhan-hak-anak.klaster3');
    
    Route::get('/klaster-4', [KlasterController::class, 'klaster4'])->name('pemenuhan-hak-anak.klaster4');
});

Route::prefix('perlindungan-khusus-anak')->group(function () {
    Route::get('/klaster-5', [KlasterController::class, 'klaster5'])->name('perlindungan-khusus-anak.klaster5');
});

Route::get('/kontak', [ContactUsController::class, 'index'])->name('kontak');
Route::post('/kontak', [ContactUsController::class, 'store']);

Route::get('/berita', function () {
    $query = News::with(['kategori', 'creator'])
                ->where('status', 1)
                ->latest();
    
    $news = $query->paginate(6);
    $categories = Kategori::withCount('news')->get();
    
    return view('beranda.berita', compact('news', 'categories'));
})->name('berita');
    
Route::get('/berita/kategori/{kategori}', function ($kategori) {
    if (config('database.default') === 'pgsql') {
        $kategoriModel = Kategori::whereRaw("LOWER(REPLACE(name, ' ', '-')) = ?", [strtolower($kategori)])
                        ->orWhereRaw("LOWER(name) LIKE ?", ['%' . str_replace('-', ' ', strtolower($kategori)) . '%'])
                        ->first();
    } else {
        $kategoriModel = Kategori::whereRaw("LOWER(REPLACE(name, ' ', '-')) = ?", [strtolower($kategori)])
                        ->orWhereRaw("LOWER(name) LIKE ?", ['%' . str_replace('-', ' ', strtolower($kategori)) . '%'])
                        ->first();
    }
    
    if (!$kategoriModel) {
        $kategoriModel = Kategori::all()->first(function($cat) use ($kategori) {
            return Str::slug($cat->name) === $kategori;
        });
    }

    $query = News::with(['kategori', 'creator'])
                ->where('status', 1)
                ->latest();
                
    if ($kategoriModel) {
        $query->where('kategori_id', $kategoriModel->id);
    } else {
        $query->whereHas('kategori', function($q) use ($kategori) {
            $cleanKategori = str_replace('-', ' ', $kategori);
            $q->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($cleanKategori) . '%']);
        });
    }
    
    $news = $query->paginate(6);
    $categories = Kategori::withCount('news')->get();
    
    return view('beranda.berita', compact('news', 'categories', 'kategori'));
})->name('berita.kategori');

Route::get('/berita/kategori/{kategori}/halaman/{page}', function ($kategori, $page) {
    if (config('database.default') === 'pgsql') {
        $kategoriModel = Kategori::whereRaw("LOWER(REPLACE(name, ' ', '-')) = ?", [strtolower($kategori)])
                        ->orWhereRaw("LOWER(name) LIKE ?", ['%' . str_replace('-', ' ', strtolower($kategori)) . '%'])
                        ->first();
    } else {
        $kategoriModel = Kategori::whereRaw("LOWER(REPLACE(name, ' ', '-')) = ?", [strtolower($kategori)])
                        ->orWhereRaw("LOWER(name) LIKE ?", ['%' . str_replace('-', ' ', strtolower($kategori)) . '%'])
                        ->first();
    }
    
    if (!$kategoriModel) {
        $kategoriModel = Kategori::all()->first(function($cat) use ($kategori) {
            return Str::slug($cat->name) === $kategori;
        });
    }
    
    $query = News::with(['kategori', 'creator'])
                ->where('status', 1)
                ->latest();
                
    if ($kategoriModel) {
        $query->where('kategori_id', $kategoriModel->id);
    } else {
        $query->whereHas('kategori', function($q) use ($kategori) {
            $cleanKategori = str_replace('-', ' ', $kategori);
            $q->whereRaw("LOWER(name) LIKE ?", ['%' . strtolower($cleanKategori) . '%']);
        });
    }
    
    $news = $query->paginate(6, ['*'], 'page', $page);
    $categories = Kategori::withCount('news')->get();
    
    if ($news->isEmpty() && $page > 1) {
        return redirect()->route('berita.kategori', $kategori);
    }
    
    return view('beranda.berita', compact('news', 'categories', 'kategori'));
})->name('berita.kategori.page');

Route::get('/berita/halaman/{page}', function ($page) {
    $query = News::with(['kategori', 'creator'])
                ->where('status', 1)
                ->latest();
    
    $news = $query->paginate(6, ['*'], 'page', $page);
    $categories = Kategori::withCount('news')->get();
    
    if ($news->isEmpty() && $page > 1) {
        return redirect()->route('berita');
    }
    
    return view('beranda.berita', compact('news', 'categories'));
})->name('berita.page');

Route::get('/berita/baca/{title}', [NewsController::class, 'show'])->name('news.show');

Route::middleware(['auth', 'admin'])->prefix('manage')->name('admin.')->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/create', [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    });
    
    Route::prefix('setting')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('setting.index');
        Route::get('/create', [SettingController::class, 'create'])->name('setting.create');
        Route::get('/edit/{setting}', [SettingController::class, 'edit'])->name('setting.edit');
    });

    Route::prefix('news')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\NewsController::class, 'index'])->name('news.index');
        Route::get('/create', [App\Http\Controllers\Admin\NewsController::class, 'create'])->name('news.create');
        Route::get('/{news}/edit', [App\Http\Controllers\Admin\NewsController::class, 'edit'])->name('news.edit');
    });

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

    Route::resource('klaster', App\Http\Controllers\Admin\KlasterController::class);
    Route::resource('indikator', App\Http\Controllers\Admin\IndikatorController::class);
});

Route::prefix('galeri')->group(function () {
    Route::get('/', function () {
        $media = Media::where(function($query) {
            $query->where('file', 'like', '%.jpg')
                  ->orWhere('file', 'like', '%.jpeg')
                  ->orWhere('file', 'like', '%.png')
                  ->orWhere('file', 'like', '%.gif');
        })->latest()->paginate(12);
        return view('beranda.galeri', compact('media'));
    })->name('galeri');
    
    Route::get('/halaman/{page}', function ($page) {
        $media = Media::where(function($query) {
            $query->where('file', 'like', '%.jpg')
                  ->orWhere('file', 'like', '%.jpeg')
                  ->orWhere('file', 'like', '%.png')
                  ->orWhere('file', 'like', '%.gif');
        })->latest()->paginate(12, ['*'], 'page', $page);
        
        if ($media->isEmpty() && $page > 1) {
            return redirect()->route('galeri');
        }
        
        return view('beranda.galeri', compact('media'));
    })->name('galeri.page')->where('page', '[0-9]+');
    
    Route::get('/{id}', function ($id) {
        $media = Media::findOrFail($id);
        $media->increment('hits');
        return view('beranda.galeri-detail', compact('media'));
    })->name('gallery.show')->where('id', '[0-9]+');
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

Route::post('/upload-image', [App\Http\Controllers\ImageUploadController::class, 'upload'])->name('upload.image');

Route::middleware(['auth'])->delete('/admin/news/{id}', [NewsController::class, 'destroy'])->name('admin.news.destroy');

Route::middleware(['auth'])->prefix('my')->name('user.')->group(function () {
    Route::prefix('news')->group(function () {
        Route::get('/', [App\Http\Controllers\User\NewsController::class, 'index'])->name('news.index');
        Route::get('/create', [App\Http\Controllers\User\NewsController::class, 'create'])->name('news.create');
        Route::get('/{news}/edit', [App\Http\Controllers\User\NewsController::class, 'edit'])->name('news.edit');
    });
});

Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/all-news', [App\Http\Controllers\User\NewsController::class, 'allNews'])->name('all.news');
});

Route::middleware(['auth', 'admin'])->prefix('manage/opd')->name('admin.opd.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\OpdController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\OpdController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\OpdController::class, 'store'])->name('store');
    Route::get('/{opd}/edit', [App\Http\Controllers\Admin\OpdController::class, 'edit'])->name('edit');
    Route::put('/{opd}', [App\Http\Controllers\Admin\OpdController::class, 'update'])->name('update');
    Route::delete('/{opd}', [App\Http\Controllers\Admin\OpdController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', 'admin'])->prefix('manage/data-dukung')->name('admin.data-dukung.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DataDukungController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\DataDukungController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\DataDukungController::class, 'store'])->name('store');
    Route::get('/{dataDukung}/edit', [App\Http\Controllers\Admin\DataDukungController::class, 'edit'])->name('edit');
    Route::put('/{dataDukung}', [App\Http\Controllers\Admin\DataDukungController::class, 'update'])->name('update');
    Route::delete('/{dataDukung}', [App\Http\Controllers\Admin\DataDukungController::class, 'destroy'])->name('destroy');
    Route::delete('/file/{file}', [App\Http\Controllers\Admin\DataDukungController::class, 'destroyFile'])->name('destroy-file');
});

Route::middleware(['auth'])->prefix('user/data-dukung')->name('user.data-dukung.')->group(function () {
    Route::get('/', [DataDukungController::class, 'index'])->name('index');
    Route::get('/all', [DataDukungController::class, 'all'])->name('all');
    Route::get('/create', [DataDukungController::class, 'create'])->name('create');
    Route::post('/', [DataDukungController::class, 'store'])->name('store');
    Route::get('/{dataDukung}/edit', [DataDukungController::class, 'edit'])->name('edit');
    Route::put('/{dataDukung}', [DataDukungController::class, 'update'])->name('update');
    Route::delete('/{dataDukung}', [DataDukungController::class, 'destroy'])->name('destroy');
    Route::delete('/file/{file}', [DataDukungController::class, 'destroyFile'])->name('destroy-file');
    Route::get('/list', [DataDukungController::class, 'list'])->name('list');
});

Route::middleware(['auth', 'admin'])->prefix('admin/data-dukung')->name('admin.data-dukung.')->group(function () {
    Route::get('/', [AdminDataDukungController::class, 'index'])->name('index');
    Route::get('/all', [AdminDataDukungController::class, 'all'])->name('all');
    Route::get('/create', [AdminDataDukungController::class, 'create'])->name('create');
    Route::post('/', [AdminDataDukungController::class, 'store'])->name('store');
    Route::get('/{dataDukung}/edit', [AdminDataDukungController::class, 'edit'])->name('edit');
    Route::put('/{dataDukung}', [AdminDataDukungController::class, 'update'])->name('update');
    Route::delete('/{dataDukung}', [AdminDataDukungController::class, 'destroy'])->name('destroy');
    Route::delete('/file/{file}', [AdminDataDukungController::class, 'destroyFile'])->name('destroy-file');
});

Route::middleware(['auth', 'admin'])->prefix('manage/program-kerja')->name('admin.program-kerja.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\ProgramKerjaController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\ProgramKerjaController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\ProgramKerjaController::class, 'store'])->name('store');
    Route::get('/{programKerja}/edit', [App\Http\Controllers\Admin\ProgramKerjaController::class, 'edit'])->name('edit');
    Route::put('/{programKerja}', [App\Http\Controllers\Admin\ProgramKerjaController::class, 'update'])->name('update');
    Route::delete('/{programKerja}', [App\Http\Controllers\Admin\ProgramKerjaController::class, 'destroy'])->name('destroy');
});

Route::get('/{url}', [DynamicPageController::class, 'show'])
    ->where('url', '.*')
    ->name('dynamic.page');