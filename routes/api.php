<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DetailTransaksiController;
use App\Http\Controllers\Api\DetailJadwalController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\JabatanPegawaiController;
use App\Http\Controllers\Api\JadwalPegawaiController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\PemilikMobilController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\AsetMobilController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Customer
Route::resource('customer', CustomerController::class);
Route::post('customer/{id_customer}', [CustomerController::class, 'update']);
Route::post('updateCustomerSendiri/{id_customer}', [CustomerController::class, 'updateCustomerSendiri']);
// Route::post('customer/{id_customer}', [CustomerController::class, 'show']);
// Route::post('customer', [CustomerController::class, 'store']);
// Route::post('customer/{id_customer}', [CustomerController::class, 'destroy']);

// Detail Transaksi
Route::resource('detail-transaksi', DetailTransaksiController::class);
Route::post('detail-transaksi/{id_detail_transaksi_mobil}', [DetailTransaksiController::class, 'update']);
// Route::post('detail-transaksi/{id_detail_transaksi_mobil}', [DetailTransaksiController::class, 'show']);
// Route::post('detail-transaksi', [DetailTransaksiController::class, 'store']);
// Route::post('detail-transaksi/{id_detail_transaksi_mobil}', [DetailTransaksiController::class, 'destroy']);

// Detail Jadwal
Route::resource('detail-jadwal', DetailJadwalController::class);
Route::post('detail-jadwal/{id_detail_jadwal}', [DetailJadwalController::class, 'update']);
// Route::post('detail-jadwal/{id_detail_jadwal}', [DetailJadwalController::class, 'show']);
// Route::post('detail-jadwal', [DetailJadwalController::class, 'store']);
// Route::post('detail-jadwal/{id_detail_jadwal}', [DetailJadwalController::class, 'destroy']);

// Driver
Route::resource('driver', DriverController::class);
Route::post('driver/{id_driver_increment}', [DriverController::class, 'update']);
Route::get('cariDriver', 'Api\DriverController@cariDriver');
Route::get('cariDriverSibuk', 'Api\DriverController@cariDriverSibuk');
// Route::post('driver/{id_driver}', [DriverController::class, 'show']);
// Route::post('driver', [DriverController::class, 'store']);
// Route::post('driver/{id_driver}', [DriverController::class, 'destroy']);

// Jabatan Pegawai
Route::resource('jabatan-pegawai', JabatanPegawaiController::class);
Route::post('jabatan-pegawai/{id_jabatan}', [JabatanPegawaiController::class, 'update']);
// Route::post('jabatan-pegawai/{id_jabatan}', [JabatanPegawaiController::class, 'show']);
// Route::post('jabatan-pegawai', [JabatanPegawaiController::class, 'store']);
// Route::post('jabatan-pegawai/{id_jabatan}', [JabatanPegawaiController::class, 'destroy']);

// Jadwal Pegawai
Route::resource('jadwal-pegawai', JadwalPegawaiController::class);
Route::post('jadwal-pegawai/{id_jadwal}', [JadwalPegawaiController::class, 'update']);
// Route::post('jadwal-pegawai/{id_jadwal}', [JadwalPegawaiController::class, 'show']);
// Route::post('jadwal-pegawai', [JadwalPegawaiController::class, 'store']);
// Route::post('jadwal-pegawai/{id_jadwal}', [JadwalPegawaiController::class, 'destroy']);

// Pegawai
Route::resource('pegawai', PegawaiController::class);
Route::post('pegawai/{id_pegawai}', [PegawaiController::class, 'update']);
// Route::post('pegawai/{id_pegawai}', [PegawaiController::class, 'show']);
// Route::post('pegawai', [PegawaiController::class, 'store']);
// Route::post('pegawai/{id_pegawai}', [PegawaiController::class, 'destroy']);

// Pemilik Mobil
Route::resource('pemilik-mobil', PemilikMobilController::class);
Route::post('pemilik-mobil/{id_pemilik_mobil}', [PemilikMobilController::class, 'update']);
Route::get('namaPemilik', 'Api\PemilikMobilController@namaPemilik');
// Route::post('pemilik-mobil/{id_pemilik_mobil}', [PemilikMobilController::class, 'show']);
// Route::post('pemilik-mobil', [PemilikMobilController::class, 'store']);
// Route::post('pemilik-mobil/{id_pemilik_mobil}', [PemilikMobilController::class, 'destroy']);

// Promo
Route::resource('promo', PromoController::class);
Route::post('promo/{id_promo}', [PromoController::class, 'update']);
Route::get('statusPromo', 'Api\PromoController@statusPromo');
Route::get('statusPromoAll', 'Api\PromoController@statusPromoAll');
// Route::post('promo/{id_promo}', [PromoController::class, 'show']);
// Route::post('promo', [PromoController::class, 'store']);
// Route::post('promo/{id_promo}', [PromoController::class, 'destroy']);

// Transaksi 
Route::resource('transaksi', TransaksiController::class);
Route::post('transaksi/{id_transaksi_increment}', [TransaksiController::class, 'update']);

// Aset Mobil 
Route::resource('aset-mobil', AsetMobilController::class);
Route::post('aset-mobil/{id_aset_mobil}', [AsetMobilController::class, 'update']);


Route::post('login', [LoginController::class, 'login']);

// =============================================================

// Route::post('login', 'Api\LoginController@login');

// Route::get('customer', 'Api\CustomerController@index');
// Route::get('customer/{id_customer_increment}', 'Api\CustomerController@show');
// Route::post('customer', 'Api\CustomerController@store');
// Route::put('customer/{id_customer_increment}', 'Api\CustomerController@update');
// Route::delete('customer/{id_customer_increment}', 'Api\CustomerController@destroy');

// Route::get('detail-transaksi', 'Api\DetailTransaksiController@index');
// Route::get('detail-transaksi/{id_detailTrs_increment}', 'Api\DetailTransaksiController@show');
// Route::post('detail-transaksi', 'Api\DetailTransaksiController@store');
// Route::put('detail-transaksi/{id_detailTrs_increment}', 'Api\DetailTransaksiController@update');
// Route::delete('detail-transaksi/{id_detailTrs_increment}', 'Api\DetailTransaksiController@destroy');

// Route::get('pemilik-mobil', 'Api\PemilikMobilController@index');
// Route::get('pemilik-mobil/{id_pemilik_mobil}', 'Api\PemilikMobilController@show');
// Route::post('pemilik-mobil', 'Api\PemilikMobilController@store');
// Route::put('pemilik-mobil/{id_pemilik_mobil}', 'Api\PemilikMobilController@update');
// Route::delete('pemilik-mobil/{id_pemilik_mobil}', 'Api\PemilikMobilController@destroy');

// Route::get('promo', 'Api\PromoController@index');
// Route::get('promo/{id_promo}', 'Api\PromoController@show');
// Route::post('promo', 'Api\PromoController@store');
// Route::put('promo/{id_promo}', 'Api\PromoController@update');
// Route::delete('promo/{id_promo}', 'Api\PromoController@destroy');

// Route::get('jabatan', 'Api\JabatanPegawaiController@index');
// Route::get('jabatan/{id_jabatan}', 'Api\JabatanPegawaiController@show');
// Route::post('jabatan', 'Api\JabatanPegawaiController@store');
// Route::put('jabatan/{id_jabatan}', 'Api\JabatanPegawaiController@update');
// Route::delete('jabatan/{id_jabatan}', 'Api\JabatanPegawaiController@destroy');

// Route::get('jadwal-pegawai', 'Api\JadwalPegawaiController@index');
// Route::get('jadwal-pegawai/{id_jadwal_increment}', 'Api\JadwalPegawaiController@show');
// Route::post('jadwal-pegawai', 'Api\JadwalPegawaiController@store');
// Route::put('jadwal-pegawai/{id_jadwal_increment}', 'Api\JadwalPegawaiController@update');
// Route::delete('jadwal-pegawai/{id_jadwal_increment}', 'Api\JadwalPegawaiController@destroy');

// Route::get('driver', 'Api\DriverController@index');
// Route::get('driver/{id_driver_increment}', 'Api\DriverController@show');
// Route::post('driver', 'Api\DriverController@store');
// Route::put('driver/{id_driver_increment}', 'Api\DriverController@update');
// Route::delete('driver/{id_driver_increment}', 'Api\DriverController@destroy');

// Route::get('aset-mobil', 'Api\AsetMobilController@index');
// Route::get('aset-mobil/{id_aset_mobil}', 'Api\AsetMobilController@show');
// Route::post('aset-mobil', 'Api\AsetMobilController@store');
// Route::put('aset-mobil/{id_aset_mobil}', 'Api\AsetMobilController@update');
// Route::delete('aset-mobil/{id_aset_mobil}', 'Api\AsetMobilController@destroy');

// Route::get('pegawai', 'Api\PegawaiController@index');
// Route::get('pegawai/{id_pegawai}', 'Api\PegawaiController@show');
// Route::post('pegawai', 'Api\PegawaiController@store');
// Route::put('pegawai/{id_pegawai}', 'Api\PegawaiController@update');
// Route::delete('pegawai/{id_pegawai}', 'Api\PegawaiController@destroy');

// Route::get('transaksi', 'Api\TransaksiController@index');
// Route::get('transaksi/{id_transaksi_increment}', 'Api\TransaksiController@show');
// Route::post('transaksi', 'Api\TransaksiController@store');
// Route::put('transaksi/{id_transaksi_increment}', 'Api\TransaksiController@update');
// Route::delete('transaksi/{id_transaksi_increment}', 'Api\TransaksiController@destroy');

// Route::get('detail-jadwal', 'Api\DetailJadwalController@index');
// Route::get('detail-jadwal/{id_detail_jadwal}', 'Api\DetailJadwalController@show');
// Route::post('detail-jadwal', 'Api\DetailJadwalController@store');
// Route::put('detail-jadwal/{id_detail_jadwal}', 'Api\DetailJadwalController@update');
// Route::delete('detail-jadwal/{id_detail_jadwal}', 'Api\DetailJadwalController@destroy');