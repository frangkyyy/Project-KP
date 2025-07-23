<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('', function () {
    return view('auth.login');
});

use Illuminate\Http\Request;
use App\Models\Peserta;

Route::get('/get-last-id', function (Request $request) {
    $kelas = $request->query('kelas');

    if ($kelas) {
        // Cari ID terakhir berdasarkan kelas
        $lastPeserta = Peserta::where('id_peserta', 'like', "$kelas-%")
            ->orderBy('id_peserta', 'desc')
            ->first();

        return response()->json([
            'last_id' => $lastPeserta ? $lastPeserta->id_peserta : null,
        ]);
    }

    return response()->json(['last_id' => null]);
});

Route::group(['middleware' => ['isAdmin', 'auth'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
    Route::resource('peserta', \App\Http\Controllers\Admin\PesertaController::class);
    Route::get('peserta/createkelas', [\App\Http\Controllers\Admin\PesertaController::class, 'createkelas'])
        ->name('peserta.createkelas');
    Route::get('peserta/{id_kelas}/editbiaya', [\App\Http\Controllers\Admin\PesertaController::class, 'editBiaya'])
        ->name('peserta.editbiaya');
    Route::put('/admin/peserta/{id_peserta}', [\App\Http\Controllers\Admin\PesertaController::class, 'update'])->name('admin.peserta.update');
    Route::put('peserta/updatebiaya/{id_kelas}', [\App\Http\Controllers\Admin\PesertaController::class, 'updateBiaya'])
        ->name('peserta.updatebiaya');
    Route::delete('/admin/peserta/{id_peserta}', [\App\Http\Controllers\Admin\PesertaController::class, 'destroy'])->name('admin.peserta.destroy');
    Route::get('/admin/peserta/get-last-id', [\App\Http\Controllers\Admin\PesertaController::class, 'getLastId'])->name('admin.peserta.getLastId');

    Route::get('pembayaran', [\App\Http\Controllers\Admin\PenetapanPembayaranController::class, 'indexpembayaran'])->name('pembayaran.indexpembayaran');

    Route::delete('pembayaran/mass_destroy', [\App\Http\Controllers\Admin\PenetapanPembayaranController::class, 'massDestroy'])->name('pembayaran.mass_destroy');

    Route::get('admin/search-siswa', [\App\Http\Controllers\Admin\PenetapanPembayaranController::class, 'searchSiswa'])->name('admin.search-siswa');

    Route::post('penetapan/storepembayaran', [\App\Http\Controllers\Admin\PenetapanPembayaranController::class, 'storePembayaran'])->name('penetapan.storepembayaran');
    Route::resource('penetapan', \App\Http\Controllers\Admin\PenetapanPembayaranController::class);
    Route::get('/penetapan/{id_peserta}', [\App\Http\Controllers\Admin\PenetapanPembayaranController::class, 'getPenetapanByPeserta']);

    Route::delete('penetapan/mass_destroy', [\App\Http\Controllers\Admin\KelasController::class, 'massDestroy'])->name('penetapan.mass_destroy');

    Route::resource('kelas', \App\Http\Controllers\Admin\KelasController::class);
    Route::resource('kursus', \App\Http\Controllers\Admin\KursusController::class);
    Route::resource('pendaftaran', \App\Http\Controllers\Admin\PendaftaranController::class);

    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard.index');

    // Resource routes with mass destroy
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
    Route::delete('permissions/mass_destroy', [\App\Http\Controllers\Admin\PermissionController::class, 'massDestroy'])->name('permissions.mass_destroy');

    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
    Route::delete('roles/mass_destroy', [\App\Http\Controllers\Admin\RoleController::class, 'massDestroy'])->name('roles.mass_destroy');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::delete('users/mass_destroy', [\App\Http\Controllers\Admin\UserController::class, 'massDestroy'])->name('users.mass_destroy');
});

Auth::routes();

