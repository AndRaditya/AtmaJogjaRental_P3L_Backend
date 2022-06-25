<?php

use App\Http\Controllers\Api\AsetMobilController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DetailJadwalController;
use App\Http\Controllers\Api\DetailTransaksiController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\JabatanPegawaiController;
use App\Http\Controllers\Api\JadwalPegawaiController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\PegawaiController;
use App\Http\Controllers\Api\PemilikMobilController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\TransaksiController;
use Illuminate\Support\Facades\Route;

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
Route::get('customer', 'Api\CustomerController@index');
Route::get('customer/{id_customer_increment}', 'Api\CustomerController@show');
Route::post('customer', 'Api\CustomerController@store');
Route::delete('customer/{id_customer_increment}', 'Api\CustomerController@destroy');

Route::post('customer/{id_customer}', [CustomerController::class, 'update']);
Route::post('updateCustomerSendiri/{id_customer}', [CustomerController::class, 'updateCustomerSendiri']);

// Detail Transaksi
Route::get('detail-transaksi', 'Api\DetailTransaksiController@index');
Route::get('detail-transaksi/{id_detail_transaksi_mobil}', 'Api\DetailTransaksiController@show');
Route::post('detail-transaksi', 'Api\DetailTransaksiController@store');
Route::delete('detail-transaksi/{id_detail_transaksi_mobil}', 'Api\DetailTransaksiController@destroy');

Route::post('detail-transaksi/{id_detail_transaksi_mobil}', [DetailTransaksiController::class, 'update']);
Route::post('updateDetailByCustomer/{id_detail_transaksi_mobil}', [DetailTransaksiController::class, 'updateDetailByCustomer']);
Route::post('updatePengembalian/{id_detail_transaksi_mobil}', [DetailTransaksiController::class, 'updatePengembalian']);
Route::post('updateRating/{id_detail_transaksi_mobil}', [DetailTransaksiController::class, 'updateRating']);
Route::delete('destroyByCustomer/{id_detail_transaksi_mobil}', [DetailTransaksiController::class, 'destroyByCustomer']);
Route::get('detailTransaksiShowByCustomer/{id_customer_increment}', 'Api\DetailTransaksiController@detailTransaksiShowByCustomer');
Route::get('detailTransaksiShowMobile/{id_customer_increment}', 'Api\DetailTransaksiController@detailTransaksiShowMobile');
Route::get('detailTransaksiDriverMobile/{id_driver_increment}', 'Api\DetailTransaksiController@detailTransaksiDriverMobile');

Route::get('cetak_pdf/{id_detail_transaksi_mobil}', 'Api\DetailTransaksiController@cetak_pdf');

// Detail Jadwal
Route::get('detail-jadwal', 'Api\DetailJadwalController@index');
Route::get('detail-jadwal/{id_detail_jadwal}', 'Api\DetailJadwalController@show');
Route::post('detail-jadwal', 'Api\DetailJadwalController@store');
Route::delete('detail-jadwal/{id_detail_jadwal}', 'Api\DetailJadwalController@destroy');

Route::post('detail-jadwal/{id_detail_jadwal}', [DetailJadwalController::class, 'update']);

// Driver
Route::get('driver', 'Api\DriverController@index');
Route::get('driver/{id_driver_increment}', 'Api\DriverController@show');
Route::post('driver', 'Api\DriverController@store');
Route::delete('driver/{id_driver_increment}', 'Api\DriverController@destroy');

Route::put('updateDriverMobile/{id_driver_increment}', [DriverController::class, 'updateDriverMobile']);
Route::put('updateStatusDriverMobile/{id_driver_increment}', [DriverController::class, 'updateStatusDriverMobile']);

Route::post('driver/{id_driver_increment}', [DriverController::class, 'update']);
Route::get('cariDriver', 'Api\DriverController@cariDriver');
Route::get('cariDriverSibuk', 'Api\DriverController@cariDriverSibuk');

// Jabatan Pegawai
Route::get('jabatan', 'Api\JabatanPegawaiController@index');
Route::get('jabatan/{id_jabatan}', 'Api\JabatanPegawaiController@show');
Route::post('jabatan', 'Api\JabatanPegawaiController@store');
Route::delete('jabatan/{id_jabatan}', 'Api\JabatanPegawaiController@destroy');

Route::post('jabatan-pegawai/{id_jabatan}', [JabatanPegawaiController::class, 'update']);

// Jadwal Pegawai
Route::get('jadwal-pegawai', 'Api\JadwalPegawaiController@index');
Route::get('jadwal-pegawai/{id_jadwal_increment}', 'Api\JadwalPegawaiController@show');
Route::post('jadwal-pegawai', 'Api\JadwalPegawaiController@store');
Route::delete('jadwal-pegawai/{id_jadwal_increment}', 'Api\JadwalPegawaiController@destroy');

Route::post('jadwal-pegawai/{id_jadwal}', [JadwalPegawaiController::class, 'update']);

// Pegawai
Route::get('pegawai', 'Api\PegawaiController@index');
Route::get('pegawai/{id_pegawai}', 'Api\PegawaiController@show');
Route::post('pegawai', 'Api\PegawaiController@store');
Route::delete('pegawai/{id_pegawai}', 'Api\PegawaiController@destroy');

Route::post('pegawai/{id_pegawai}', [PegawaiController::class, 'update']);
Route::get('cariPegawai_Shift', 'Api\PegawaiController@cariPegawai_Shift');

// Pemilik Mobil
Route::get('pemilik-mobil', 'Api\PemilikMobilController@index');
Route::get('pemilik-mobil/{id_pemilik_mobil}', 'Api\PemilikMobilController@show');
Route::post('pemilik-mobil', 'Api\PemilikMobilController@store');
Route::delete('pemilik-mobil/{id_pemilik_mobil}', 'Api\PemilikMobilController@destroy');

Route::post('pemilik-mobil/{id_pemilik_mobil}', [PemilikMobilController::class, 'update']);
Route::get('namaPemilik', 'Api\PemilikMobilController@namaPemilik');

// Promo
Route::get('promo', 'Api\PromoController@index');
Route::get('promo/{id_promo}', 'Api\PromoController@show');
Route::post('promo', 'Api\PromoController@store');
Route::delete('promo/{id_promo}', 'Api\PromoController@destroy');

Route::post('promo/{id_promo}', [PromoController::class, 'update']);
Route::get('statusPromo', 'Api\PromoController@statusPromo');
Route::get('statusPromoAll', 'Api\PromoController@statusPromoAll');

// Transaksi
Route::get('transaksi', 'Api\TransaksiController@index');
Route::get('transaksi/{id_transaksi_increment}', 'Api\TransaksiController@show');
Route::post('transaksi', 'Api\TransaksiController@store');
Route::delete('transaksi/{id_transaksi_increment}', 'Api\TransaksiController@destroy');

Route::post('updateFromPegawai/{id_transaksi_increment}', [TransaksiController::class, 'updateFromPegawai']);
Route::post('updateFromCustomer/{id_transaksi_increment}', [TransaksiController::class, 'updateFromCustomer']);
Route::get('transaksiShowByCustomer/{id_customer_increment}', 'Api\TransaksiController@transaksiShowByCustomer');
Route::get('transaksiShowByCustomerId/{id_customer_increment}', 'Api\TransaksiController@transaksiShowByCustomerId');

// Aset Mobil
Route::get('aset-mobil', 'Api\AsetMobilController@index');
Route::get('aset-mobil/{id_aset_mobil}', 'Api\AsetMobilController@show');
Route::post('aset-mobil', 'Api\AsetMobilController@store');
Route::delete('aset-mobil/{id_aset_mobil}', 'Api\AsetMobilController@destroy');

Route::post('aset-mobil/{id_aset_mobil}', [AsetMobilController::class, 'update']);
Route::get('cariKontrak', 'Api\AsetMobilController@cariKontrak');
Route::get('mobilTersedia', 'Api\AsetMobilController@mobilTersedia');

Route::post('login', [LoginController::class, 'login']);

Route::get('cariLaporanPendapatan/{tahun}/{bulan}', 'Api\PDFController@cariLaporanPendapatan');
Route::get('cariLaporanPenyewaan/{tahun}/{bulan}', 'Api\PDFController@cariLaporanPenyewaan');
Route::get('cariLaporanTop5Driver/{tahun}/{bulan}', 'Api\PDFController@cariLaporanTop5Driver');
Route::get('cariLaporanTop5Customer/{tahun}/{bulan}', 'Api\PDFController@cariLaporanTop5Customer');
Route::get('cariLaporanPerformaDriver/{tahun}/{bulan}', 'Api\PDFController@cariLaporanPerformaDriver');
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
// Route::delete('pemilik-mobil/{id_pemilik_mobil}', 'Api\PemilikMobilController@destroy');
// Route::put('pemilik-mobil/{id_pemilik_mobil}', 'Api\PemilikMobilController@update');

// Route::get('promo', 'Api\PromoController@index');
// Route::get('promo/{id_promo}', 'Api\PromoController@show');
// Route::post('promo', 'Api\PromoController@store');
// Route::delete('promo/{id_promo}', 'Api\PromoController@destroy');
// Route::put('promo/{id_promo}', 'Api\PromoController@update');

// Route::get('jabatan', 'Api\JabatanPegawaiController@index');
// Route::get('jabatan/{id_jabatan}', 'Api\JabatanPegawaiController@show');
// Route::post('jabatan', 'Api\JabatanPegawaiController@store');
// Route::delete('jabatan/{id_jabatan}', 'Api\JabatanPegawaiController@destroy');
// Route::put('jabatan/{id_jabatan}', 'Api\JabatanPegawaiController@update');

// Route::get('jadwal-pegawai', 'Api\JadwalPegawaiController@index');
// Route::get('jadwal-pegawai/{id_jadwal_increment}', 'Api\JadwalPegawaiController@show');
// Route::post('jadwal-pegawai', 'Api\JadwalPegawaiController@store');
// Route::delete('jadwal-pegawai/{id_jadwal_increment}', 'Api\JadwalPegawaiController@destroy');
// Route::put('jadwal-pegawai/{id_jadwal_increment}', 'Api\JadwalPegawaiController@update');

// Route::get('driver', 'Api\DriverController@index');
// Route::get('driver/{id_driver_increment}', 'Api\DriverController@show');
// Route::post('driver', 'Api\DriverController@store');
// Route::delete('driver/{id_driver_increment}', 'Api\DriverController@destroy');
// Route::put('driver/{id_driver_increment}', 'Api\DriverController@update');

// Route::get('aset-mobil', 'Api\AsetMobilController@index');
// Route::get('aset-mobil/{id_aset_mobil}', 'Api\AsetMobilController@show');
// Route::post('aset-mobil', 'Api\AsetMobilController@store');
// Route::delete('aset-mobil/{id_aset_mobil}', 'Api\AsetMobilController@destroy');
// Route::put('aset-mobil/{id_aset_mobil}', 'Api\AsetMobilController@update');

// Route::get('pegawai', 'Api\PegawaiController@index');
// Route::get('pegawai/{id_pegawai}', 'Api\PegawaiController@show');
// Route::post('pegawai', 'Api\PegawaiController@store');
// Route::delete('pegawai/{id_pegawai}', 'Api\PegawaiController@destroy');
// Route::put('pegawai/{id_pegawai}', 'Api\PegawaiController@update');

// Route::get('transaksi', 'Api\TransaksiController@index');
// Route::get('transaksi/{id_transaksi_increment}', 'Api\TransaksiController@show');
// Route::post('transaksi', 'Api\TransaksiController@store');
// Route::delete('transaksi/{id_transaksi_increment}', 'Api\TransaksiController@destroy');
// Route::put('transaksi/{id_transaksi_increment}', 'Api\TransaksiController@update');

// Route::get('detail-jadwal', 'Api\DetailJadwalController@index');
// Route::get('detail-jadwal/{id_detail_jadwal}', 'Api\DetailJadwalController@show');
// Route::post('detail-jadwal', 'Api\DetailJadwalController@store');
// Route::delete('detail-jadwal/{id_detail_jadwal}', 'Api\DetailJadwalController@destroy');
// Route::put('detail-jadwal/{id_detail_jadwal}', 'Api\DetailJadwalController@update');