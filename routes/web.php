<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PengaduanController;

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

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'petugas':
                return redirect()->route('petugas.dashboard');
            case 'guru':
                return redirect()->route('guru.dashboard');
            case 'siswa':
                return redirect()->route('siswa.dashboard');
            default:
                return redirect('/');
        }
    })->name('dashboard');

    Route::prefix('admin')->middleware(['auth', \App\Http\Middleware\CheckRole::class . ':admin'])->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
    });

    Route::prefix('petugas')->middleware(['auth', \App\Http\Middleware\CheckRole::class . ':petugas'])->group(function () {
        Route::get('/dashboard', function () {
            return view('petugas.dashboard');
        })->name('petugas.dashboard');
    });

    Route::prefix('guru')->middleware(['auth', \App\Http\Middleware\CheckRole::class . ':guru'])->group(function () {
        Route::get('/dashboard', function () {
            return view('guru.dashboard');
        })->name('guru.dashboard');
    });

    Route::prefix('siswa')->middleware(['auth', \App\Http\Middleware\CheckRole::class . ':siswa'])->group(function () {
        Route::get('/dashboard', function () {
            $user = auth()->user();
            $query = \App\Models\Pengaduan::where('id_user', $user->id_user);
            $totalPengaduan = $query->count();
            
            $data = [
                'totalPengaduan' => $totalPengaduan,
                'selesaiCount' => \App\Models\Pengaduan::where('id_user', $user->id_user)->where('status', 'Selesai')->count(),
                'prosesCount' => \App\Models\Pengaduan::where('id_user', $user->id_user)->whereIn('status', ['Diajukan', 'Disetujui', 'Diproses'])->count(),
                'recentPengaduans' => \App\Models\Pengaduan::where('id_user', $user->id_user)
                    ->orderBy('tgl_pengajuan', 'desc')
                    ->take(5)
                    ->get()
            ];
            
            return view('siswa.dashboard', $data);
        })->name('siswa.dashboard');

        // Pengaduan routes
        Route::get('/pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
        Route::get('/pengaduan/create', [PengaduanController::class, 'create'])->name('pengaduan.create');
        Route::post('/pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::get('/pengaduan/{pengaduan}', [PengaduanController::class, 'show'])->name('pengaduan.show');
        Route::get('/pengaduan/{pengaduan}/edit', [PengaduanController::class, 'edit'])->name('pengaduan.edit');
        Route::put('/pengaduan/{pengaduan}', [PengaduanController::class, 'update'])->name('pengaduan.update');
        Route::delete('/pengaduan/{pengaduan}', [PengaduanController::class, 'destroy'])->name('pengaduan.destroy');
    });
});
