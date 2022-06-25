<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Aset_Mobil_10144;
use App\Models\Detail_Transaksi_Mobil_10144;
use App\Models\Driver_10144;
use App\Models\Promo_10144;
// use DB;
use App\Models\Transaksi_Mobil_10144;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDF;

class DetailTransaksiController extends Controller
{
    public function index()
    {
        $detail_transaksis = DB::table('detail__transaksi__mobil_10144s')
            ->join('driver_10144s', 'detail__transaksi__mobil_10144s.id_driver_increment', '=', 'driver_10144s.id_driver_increment')
            ->join('aset__mobil_10144s', 'detail__transaksi__mobil_10144s.id_aset_mobil', '=', 'aset__mobil_10144s.id_aset_mobil')
            ->join('transaksi__mobil_10144s', 'detail__transaksi__mobil_10144s.id_transaksi_increment', '=', 'transaksi__mobil_10144s.id_transaksi_increment')
            ->select('detail__transaksi__mobil_10144s.*', 'transaksi__mobil_10144s.id_transaksi_mobil',
                'transaksi__mobil_10144s.tanggal_transaksi', 'aset__mobil_10144s.nama_mobil', 'aset__mobil_10144s.plat_nomor_mobil', 'transaksi__mobil_10144s.metode_pembayaran',
                'driver_10144s.id_driver', 'driver_10144s.nama_driver', 'transaksi__mobil_10144s.*')
            ->get();

        if (count($detail_transaksis) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detail_transaksis,
            ], 200);
        } // return data semua detail_transaksis dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null,
        ], 400); // return message data detail_transaksis kosong
    }

    public function detailTransaksiShowByCustomer($id_customer_increment)
    {
        $detail_transaksi = DB::table('detail__transaksi__mobil_10144s')
            ->select('detail__transaksi__mobil_10144s.*', 'transaksi__mobil_10144s.*', 'driver_10144s.*', 'aset__mobil_10144s.*')
            ->join('driver_10144s', 'detail__transaksi__mobil_10144s.id_driver_increment', '=', 'driver_10144s.id_driver_increment')
            ->join('aset__mobil_10144s', 'detail__transaksi__mobil_10144s.id_aset_mobil', '=', 'aset__mobil_10144s.id_aset_mobil')
            ->join('transaksi__mobil_10144s', 'detail__transaksi__mobil_10144s.id_transaksi_increment', '=', 'transaksi__mobil_10144s.id_transaksi_increment')
            ->where('transaksi__mobil_10144s.id_customer_increment', $id_customer_increment)
            ->get();

        if (!is_null($detail_transaksi)) {
            return response([
                'message' => 'Retrieve detail transaksi Success',
                'data' => $detail_transaksi,
            ], 200);
        } // return data transaksi yang ditemukan dalam bentuk json

        return response([
            'message' => 'detail transaksi Not Found',
            'data' => null,
        ], 404); // return message saat data transaksi tidak ditemukan
    }

    public function detailTransaksiShowMobile($id_customer_increment)
    {
        $detail_transaksi = DB::select("SELECT DET.id_detailTrs_increment, DET.id_detail_transaksi_mobil, TRS.id_transaksi_mobil, TRS.tanggal_transaksi, M.nama_mobil,
                                CUS.nama_customer, PGW.nama_pegawai, DET.diskon_pembayaran, M.harga_sewa_mobil, D.nama_driver, DET.tanggal_waktu_mulaiSewa,
                                DET.tanggal_waktu_selesaiSewa, DET.tanggal_pengembalian, DET.jumlah_pembayaran, DET.denda, DET.status_transaksi
                                FROM customer_10144s CUS JOIN transaksi__mobil_10144s TRS ON (CUS.id_customer_increment = TRS.id_customer_increment)
                                JOIN pegawai_10144s PGW ON (PGW.id_pegawai = TRS.id_pegawai)
                                JOIN detail__transaksi__mobil_10144s DET ON (TRS.id_transaksi_increment = DET.id_transaksi_increment)
                                JOIN aset__mobil_10144s M ON (DET.id_aset_mobil = M.id_aset_mobil)
                                JOIN driver_10144s D ON (DET.id_driver_increment = D.id_driver_increment)
                                WHERE (SELECT CUS.id_customer_increment) = $id_customer_increment AND (SELECT DET.status_transaksi) = 'Transaksi Selesai'");

        if (!is_null($detail_transaksi)) {
            return response([
                'message' => 'Retrieve detail transaksi Success',
                'data' => $detail_transaksi,
            ], 200);
        } // return data transaksi yang ditemukan dalam bentuk json

        return response([
            'message' => 'detail transaksi Not Found',
            'data' => null,
        ], 404); // return message saat data transaksi tidak ditemukan
    }

    public function detailTransaksiDriverMobile($id_driver_increment)
    {
        $detail_transaksi = DB::select("SELECT DET.id_detailTrs_increment, DET.id_detail_transaksi_mobil, TRS.id_transaksi_mobil, TRS.tanggal_transaksi, M.nama_mobil,
                                CUS.nama_customer, PGW.nama_pegawai, DET.diskon_pembayaran, M.harga_sewa_mobil, D.nama_driver, DET.tanggal_waktu_mulaiSewa,
                                DET.tanggal_waktu_selesaiSewa, DET.tanggal_pengembalian, DET.jumlah_pembayaran, DET.denda, DET.status_transaksi
                                FROM customer_10144s CUS JOIN transaksi__mobil_10144s TRS ON (CUS.id_customer_increment = TRS.id_customer_increment)
                                JOIN pegawai_10144s PGW ON (PGW.id_pegawai = TRS.id_pegawai)
                                JOIN detail__transaksi__mobil_10144s DET ON (TRS.id_transaksi_increment = DET.id_transaksi_increment)
                                JOIN aset__mobil_10144s M ON (DET.id_aset_mobil = M.id_aset_mobil)
                                JOIN driver_10144s D ON (DET.id_driver_increment = D.id_driver_increment)
                                WHERE (SELECT D.id_driver_increment) = $id_driver_increment AND (SELECT DET.status_transaksi) = 'Transaksi Selesai'");

        if (!is_null($detail_transaksi)) {
            return response([
                'message' => 'Retrieve detail transaksi Success',
                'data' => $detail_transaksi,
            ], 200);
        } // return data transaksi yang ditemukan dalam bentuk json

        return response([
            'message' => 'detail transaksi Not Found',
            'data' => null,
        ], 404); // return message saat data transaksi tidak ditemukan
    }

    public function cetak_pdf($id_detailTrs_increment)
    {
        $detail_transaksis = DB::table('detail__transaksi__mobil_10144s')
            ->select('detail__transaksi__mobil_10144s.id_detail_transaksi_mobil AS id_detail_transaksi',
                'transaksi__mobil_10144s.id_transaksi_mobil AS id_transaksi',
                'transaksi__mobil_10144s.tanggal_transaksi AS tgl_transaksi',
                'driver_10144s.nama_driver AS nama_driver',
                'detail__transaksi__mobil_10144s.tanggal_waktu_mulaiSewa AS tgl_mulai',
                'detail__transaksi__mobil_10144s.tanggal_waktu_selesaiSewa AS tgl_selesai',
                'detail__transaksi__mobil_10144s.tanggal_pengembalian AS tgl_pengembalian',
                'aset__mobil_10144s.nama_mobil AS nama_mobil',
                'aset__mobil_10144s.harga_sewa_mobil AS harga_mobil',
                'detail__transaksi__mobil_10144s.durasi AS durasi',
                'detail__transaksi__mobil_10144s.biaya_mobil AS total_biaya_mobil',
                'driver_10144s.tarif_driver_harian AS biaya_driver_satuan',
                'detail__transaksi__mobil_10144s.biaya_driver AS total_biaya_driver',
                'detail__transaksi__mobil_10144s.biaya_mobil_driver AS total_driver_mobil',
                'detail__transaksi__mobil_10144s.diskon_pembayaran AS diskon',
                'detail__transaksi__mobil_10144s.denda AS denda',
                'detail__transaksi__mobil_10144s.jumlah_pembayaran AS total_biaya',
                'pegawai_10144s.nama_pegawai AS nama_pegawai',
                'promo_10144s.kode_promo AS kode_promo',
                'customer_10144s.nama_customer AS nama_customer')
            ->join('driver_10144s', 'detail__transaksi__mobil_10144s.id_driver_increment', '=', 'driver_10144s.id_driver_increment')
            ->join('aset__mobil_10144s', 'detail__transaksi__mobil_10144s.id_aset_mobil', '=', 'aset__mobil_10144s.id_aset_mobil')
            ->join('transaksi__mobil_10144s', 'detail__transaksi__mobil_10144s.id_transaksi_increment', '=', 'transaksi__mobil_10144s.id_transaksi_increment')

            ->join('promo_10144s', 'transaksi__mobil_10144s.id_promo', '=', 'promo_10144s.id_promo')
            ->join('pegawai_10144s', 'transaksi__mobil_10144s.id_pegawai', '=', 'pegawai_10144s.id_pegawai')
            ->join('customer_10144s', 'transaksi__mobil_10144s.id_customer_increment', '=', 'customer_10144s.id_customer_increment')

            ->where('id_detailTrs_increment', '=', $id_detailTrs_increment)
            ->first();

        $data = [
            'id_detail_transaksi' => $detail_transaksis->id_detail_transaksi,
            'id_transaksi' => $detail_transaksis->id_transaksi,
            'tgl_transaksi' => $detail_transaksis->tgl_transaksi,
            'nama_driver' => $detail_transaksis->nama_driver,
            'tgl_mulai' => $detail_transaksis->tgl_mulai,
            'tgl_selesai' => $detail_transaksis->tgl_selesai,
            'tgl_pengembalian' => $detail_transaksis->tgl_pengembalian,
            'nama_mobil' => $detail_transaksis->nama_mobil,
            'harga_mobil' => $detail_transaksis->harga_mobil,
            'durasi' => $detail_transaksis->durasi,
            'total_biaya_mobil' => $detail_transaksis->total_biaya_mobil,
            'biaya_driver_satuan' => $detail_transaksis->biaya_driver_satuan,
            'total_biaya_driver' => $detail_transaksis->total_biaya_driver,
            'total_driver_mobil' => $detail_transaksis->total_driver_mobil,
            'diskon' => $detail_transaksis->diskon,
            'denda' => $detail_transaksis->denda,
            'total_biaya' => $detail_transaksis->total_biaya,
            'nama_pegawai' => $detail_transaksis->nama_pegawai,
            'kode_promo' => $detail_transaksis->kode_promo,
            'nama_customer' => $detail_transaksis->nama_customer,
        ];

        $pdf = PDF::loadview('detail_transaksi_pdf', $data);
        return $pdf->download('Nota Pembayaran.pdf');
    }

    public function show($id_detailTrs_increment)
    {
        $detail_transaksi = Detail_Transaksi_Mobil_10144::where('id_detailTrs_increment', $id_detailTrs_increment)->first(); // mencari data Detail transaksi berdasarkan id

        if (!is_null($detail_transaksi)) {
            return response([
                'message' => 'Retrieve detail transaksi Success',
                'data' => $detail_transaksi,
            ], 200);
        } // return data Detail transaksi yang ditemukan dalam bentuk json

        return response([
            'message' => 'Detail transaksi Not Found',
            'data' => null,
        ], 404); // return message saat data Detail transaksi tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client

        $storeData['tanggal_waktu_mulaiSewa'] = Carbon::parse($storeData['tanggal_waktu_mulaiSewa'])->format('YYYY-MM-DDThh:mm');
        $storeData['tanggal_waktu_selesaiSewa'] = Carbon::parse($storeData['tanggal_waktu_selesaiSewa'])->format('YYYY-MM-DDThh:mm');

        $validate = Validator::make($storeData, [
            // 'tanggal_waktu_mulaiSewa' => 'required|after_or_equal:' . $transaksi->tanggal_transaksi,
            'tanggal_waktu_mulaiSewa' => 'required',
            'tanggal_waktu_selesaiSewa' => 'required|after_or_equal:tanggal_waktu_mulaiSewa',
            'tanggal_pengembalian' => 'nullable',
            // 'bukti_transfer' => 'nullable',
            // 'bukti_transfer.*' => 'max:1024|mimes:jpg,png,jpeg|image',
            'jenis_transaksi' => 'required',
            'id_transaksi_increment' => 'required',
            'id_aset_mobil' => 'required',
        ]); // membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        // return error invalid input

        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment', $request->id_transaksi_increment)->first();
        $checkDetailTransaksi = Detail_Transaksi_Mobil_10144::count();

        $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil', $request->id_aset_mobil)->first();
        $tempHargaSewa = $aset_mobil->harga_sewa_mobil;

        $tglMulai = $request->tanggal_waktu_mulaiSewa;
        $tglAkhir = $request->tanggal_waktu_selesaiSewa;
        // $tglPengembalian = $request->tanggal_pengembalian;
        $dateTime1 = new DateTime($tglMulai);
        $dateTime2 = new DateTime($tglAkhir);
        // $dateTime3 = new DateTime($tglPengembalian);

        $selisihHari = $dateTime1->diff($dateTime2);
        $totalHari = $selisihHari->format('%a');

        //generate id transaksi
        if ($checkDetailTransaksi == 0) {
            if ($request->jenis_transaksi == 'Penyewaan Mobil + Driver') {
                $id_awal = "TRN" . date('ymd') . '01-';
                $id_detailTransaksi_format = $id_awal . sprintf('%03d', 1);

                // $cariTarifDriver = Driver_10144::where('id_driver_increment', $request->id_driver_increment)->first();
                // $tempTarifDriver = $cariTarifDriver->tarif_driver_harian;
                $tempTarifDriver = 0;
            } else if ($request->jenis_transaksi == 'Penyewaan Mobil Tanpa Driver') {
                $id_awal = "TRN" . date('ymd') . '00-';
                $id_detailTransaksi_format = $id_awal . sprintf('%03d', 1);

                $tempTarifDriver = 0;
            }
        } else {
            if ($request->jenis_transaksi == 'Penyewaan Mobil + Driver') {
                $id_awal = "TRN" . date('ymd') . '01-';
                $id_angka = DB::table('detail__transaksi__mobil_10144s')->select('id_detailTrs_increment')->orderBy('id_detailTrs_increment', 'desc')->first();
                $id_angka_temp = $id_angka->id_detailTrs_increment + 1;
                $id_detailTransaksi_format = $id_awal . sprintf('%03d', $id_angka_temp);

                // $cariTarifDriver = Driver_10144::where('id_driver_increment', $request->id_driver_increment)->first();
                // $tempTarifDriver = $cariTarifDriver->tarif_driver_harian;
                $tempTarifDriver = 0;
            } else if ($request->jenis_transaksi == 'Penyewaan Mobil Tanpa Driver') {
                $id_awal = "TRN" . date('ymd') . '00-';
                $id_angka = DB::table('detail__transaksi__mobil_10144s')->select('id_detailTrs_increment')->orderBy('id_detailTrs_increment', 'desc')->first();
                $id_angka_temp = $id_angka->id_detailTrs_increment + 1;
                $id_detailTransaksi_format = $id_awal . sprintf('%03d', $id_angka_temp);

                $tempTarifDriver = 0;
            }
        }

        $promo = $transaksi->id_promo;
        if (!is_null($promo)) {
            $id_promo = Promo_10144::where('id_promo', $promo)->first();
            $tempDiskon = $id_promo->diskon;
            $totalDiskon = 1 - ($tempDiskon / 100);

            $kalkulasiPembayaran = ((($tempHargaSewa * $totalHari)) * $totalDiskon);
        } else {
            $kalkulasiPembayaran = ($tempHargaSewa * $totalHari);
        }

        $tempMetodePembayaran = $transaksi->metode_pembayaran;
        if ($tempMetodePembayaran === 'Transfer') {

            $tempStatusTransaksi = 'Belum Diverifikasi oleh Customer Service';
        } else if ($tempMetodePembayaran === 'Cash') {
            $uploadBuktiPembayaran = null;
            $tempStatusTransaksi = 'Pembayaran di tempat';
        }

        $tempIdDriver = '7';
        $tempBiayaMobil = ($tempHargaSewa * $totalHari);
        $tempBiayaDriver = ($tempTarifDriver * $totalHari);
        $tempBiayaMobilDriver = $tempBiayaMobil + $tempBiayaDriver;
        $tempTotalDiskon = $tempBiayaMobilDriver * ($tempDiskon / 100);
        
        $detail_transaksi = Detail_Transaksi_Mobil_10144::create([
            'diskon_pembayaran' => $tempTotalDiskon,
            'biaya_mobil_driver' => $tempBiayaMobilDriver,
            'biaya_mobil' => $tempBiayaMobil,
            'biaya_driver' => $tempBiayaDriver,
            'durasi' => $totalHari,
            'status_transaksi' => $tempStatusTransaksi,
            // 'bukti_transfer' => $uploadBuktiPembayaran,
            'tanggal_waktu_mulaiSewa' => $request->tanggal_waktu_mulaiSewa,
            'tanggal_waktu_selesaiSewa' => $request->tanggal_waktu_selesaiSewa,
            'id_driver_increment' => $tempIdDriver,
            'id_transaksi_increment' => $request->id_transaksi_increment,
            'id_aset_mobil' => $request->id_aset_mobil,
            'jenis_transaksi' => $request->jenis_transaksi,
            'id_detail_transaksi_mobil' => $id_detailTransaksi_format,
            'jumlah_pembayaran' => $kalkulasiPembayaran,
        ]);
        return response([
            'message' => 'Add Detail transaksi Success',
            'data' => $detail_transaksi,
        ], 200); // return data Detail transaksi baru dalam bentuk json
    }

    public function destroy($id_detailTrs_increment)
    {
        $detail_transaksi = Detail_Transaksi_Mobil_10144::where('id_detailTrs_increment', $id_detailTrs_increment); // mencari data Detail transaksi berdasarkan id

        if (is_null($detail_transaksi)) {
            return response([
                'message' => 'Detail transaksi Not Found',
                'data' => null,
            ], 404);
        } // return message saat data Detail transaksi tidak ditemukan

        if ($detail_transaksi->delete()) {
            return response([
                'message' => 'Delete Detail transaksi Success',
                'data' => $detail_transaksi,
            ], 200);
        } // return message saat berhasil menghapus data Detail transaksi

        return response([
            'message' => 'Delete Detail transaksi Failed',
            'data' => null,
        ], 400); // return message saat gagal menghapus data Detail transaksi
    }

    public function destroyByCustomer($id_detailTrs_increment)
    {
        $detail_transaksi = Detail_Transaksi_Mobil_10144::where('id_detailTrs_increment', $id_detailTrs_increment); // mencari data Detail transaksi berdasarkan id
        $detail_transaksi_cari = Detail_Transaksi_Mobil_10144::where('id_detailTrs_increment', $id_detailTrs_increment)->first();

        if (is_null($detail_transaksi)) {
            return response([
                'message' => 'Detail transaksi Not Found',
                'data' => null,
            ], 404);
        } // return message saat data Detail transaksi tidak ditemukan

        if ($detail_transaksi_cari->status_transaksi === 'Belum Diverifikasi oleh Customer Service') {
            if ($detail_transaksi->delete()) {
                return response([
                    'message' => 'Delete Detail transaksi Success',
                    'data' => $detail_transaksi,
                ], 200);
            } // return message saat berhasil menghapus data Detail transaksi

            return response([
                'message' => 'Delete Detail transaksi Failed',
                'data' => null,
            ], 400); // return message saat gagal menghapus data Detail transaksi
        } else if ($detail_transaksi_cari->status_transaksi === 'Sudah Terverifikasi') {
            return response([
                'message' => 'Anda Sudah Terverifikasi, Tidak Dapat Batal',
                'data' => null,
            ], 404);
        }
    }

    public function update(Request $request, $id_detailTrs_increment)
    {
        $detail_transaksi = Detail_Transaksi_Mobil_10144::where('id_detailTrs_increment', $id_detailTrs_increment)->first();
        if (is_null($detail_transaksi)) {
            return response([
                'message' => 'Detail transaksi Not Found',
                'data' => null,
            ], 404);
        } // return message saat data Detail transaksi tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment', $request->id_transaksi_increment)->first();
        $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil', $request->id_aset_mobil)->first();

        $detail_transaksi->tanggal_waktu_mulaiSewa = Carbon::parse($updateData['tanggal_waktu_mulaiSewa'])->format('YYYY-MM-DDThh:mm');
        $detail_transaksi->tanggal_waktu_selesaiSewa = Carbon::parse($updateData['tanggal_waktu_selesaiSewa'])->format('YYYY-MM-DDThh:mm');

        $validate = Validator::make($updateData, [
            'tanggal_waktu_mulaiSewa' => 'required',
            'tanggal_waktu_selesaiSewa' => 'required|after_or_equal:tanggal_waktu_mulaiSewa',
            'id_driver_increment' => 'nullable',
            'id_transaksi_increment' => 'required',
            'id_aset_mobil' => 'nullable',
            'status_transaksi' => 'required',
        ]); // membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        // return error invalid input

        $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil', $request->id_aset_mobil)->first();
        $tempHargaSewa = $aset_mobil->harga_sewa_mobil;

        $tglMulai = $request->tanggal_waktu_mulaiSewa;
        $tglAkhir = $request->tanggal_waktu_selesaiSewa;
        $dateTime1 = new DateTime($tglMulai);
        $dateTime2 = new DateTime($tglAkhir);

        $selisihHari = $dateTime1->diff($dateTime2);
        $totalHari = $selisihHari->format('%a');

        $cariDriver = Driver_10144::where('id_driver_increment', $request->id_driver_increment)->first();

        if ($detail_transaksi->jenis_transaksi === "Penyewaan Mobil Tanpa Driver") {
            $tempIdDriver = 7;
            $tempTarifDriver = 0;
        } else if ($detail_transaksi->jenis_transaksi === "Penyewaan Mobil + Driver" && $request->id_driver_increment !== 7) {
            $tempIdDriver = $request->id_driver_increment;
            $tempTarifDriver = $cariDriver->tarif_driver_harian;
        }

        // if($request->id_driver_increment !== 7){

        // }else if($request->id_driver_increment === 7){

        // }

        // if(!is_null($detail_transaksi->id_driver_increment) || $detail_transaksi->id_driver_increment > 0){
        //     $tempTarifDriver = $cariDriver->tarif_driver_harian;
        // }else{
        //     $tempTarifDriver = 0;
        // }

        $promo = $transaksi->id_promo;
        if (!is_null($promo)) {
            $id_promo = Promo_10144::where('id_promo', $promo)->first();
            $tempDiskon = $id_promo->diskon;
            $totalDiskon = 1 - ($tempDiskon / 100);

            $kalkulasiPembayaran = ((($tempHargaSewa * $totalHari) + ($tempTarifDriver * $totalHari)) * $totalDiskon);
        } else {
            $kalkulasiPembayaran = (($tempHargaSewa * $totalHari) + ($tempTarifDriver * $totalHari));
        }

        $tempBiayaMobil = ($tempHargaSewa * $totalHari);
        $tempBiayaDriver = ($tempTarifDriver * $totalHari);
        $tempBiayaMobilDriver = $tempBiayaMobil + $tempBiayaDriver;
        $tempTotalDiskon = $tempBiayaMobilDriver * ($tempDiskon / 100);

        $detail_transaksi->diskon_pembayaran = $tempTotalDiskon;
        $detail_transaksi->biaya_mobil_driver = $tempBiayaMobilDriver;
        $detail_transaksi->biaya_mobil = $tempBiayaMobil;
        $detail_transaksi->biaya_driver = $tempBiayaDriver;

        $detail_transaksi->durasi = $totalHari;
        $detail_transaksi->jumlah_pembayaran = $kalkulasiPembayaran;
        $detail_transaksi->id_driver_increment = $tempIdDriver;
        $detail_transaksi->tanggal_waktu_mulaiSewa = $updateData['tanggal_waktu_mulaiSewa'];
        $detail_transaksi->tanggal_waktu_selesaiSewa = $updateData['tanggal_waktu_selesaiSewa'];
        $detail_transaksi->status_transaksi = $updateData['status_transaksi'];

        if ($detail_transaksi->save()) {
            return response([
                'message' => 'Update Detail transaksi Success',
                'data' => $detail_transaksi,
            ], 200);
        } // return data Detail transaksi yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update Detail transaksi Failed',
            'data' => null,
        ], 400); // return message saat Detail transaksi gagal diedit
    }

    public function updatePengembalian(Request $request, $id_detailTrs_increment)
    {
        $detail_transaksi = Detail_Transaksi_Mobil_10144::where('id_detailTrs_increment', $id_detailTrs_increment)->first();
        if (is_null($detail_transaksi)) {
            return response([
                'message' => 'Detail transaksi Not Found',
                'data' => null,
            ], 404);
        } // return message saat data Detail transaksi tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment', $detail_transaksi->id_transaksi_increment)->first();
        $detail_transaksi->tanggal_pengembalian = Carbon::parse($updateData['tanggal_pengembalian'])->format('YYYY-MM-DDThh:mm');

        $validate = Validator::make($updateData, [
            // 'tanggal_waktu_mulaiSewa' => 'required',
            // 'tanggal_waktu_selesaiSewa' => 'required|after_or_equal:tanggal_waktu_mulaiSewa',
            'tanggal_pengembalian' => 'required|after_or_equal: ' . $detail_transaksi->tanggal_waktu_selesaiSewa,
            'status_transaksi' => 'required',
        ]); // membuat rule validasi input

        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
        // return error invalid input

        $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil', $detail_transaksi->id_aset_mobil)->first();
        $tempHargaSewa = $aset_mobil->harga_sewa_mobil;

        $cariDriver = Driver_10144::where('id_driver_increment', $detail_transaksi->id_driver_increment)->first();
        $tempTarifDriver = $cariDriver->tarif_driver_harian;

        $tglMulai = $detail_transaksi->tanggal_waktu_mulaiSewa;
        $tglAkhir = $detail_transaksi->tanggal_waktu_selesaiSewa;
        $tglPengembalian = $request->tanggal_pengembalian;
        $dateTime1 = new DateTime($tglMulai);
        $dateTime2 = new DateTime($tglAkhir);
        $dateTime3 = new DateTime($tglPengembalian);

        $selisihHari = $dateTime1->diff($dateTime2);
        $totalHari = $selisihHari->format('%a');

        $selisihJam = $dateTime2->diff($dateTime3);
        $totalJamDenda = $selisihJam->h;
        $totalHariDenda = $selisihJam->d;
        $cariDriver = Driver_10144::where('id_driver_increment', $detail_transaksi->id_driver_increment)->first();

        if ($detail_transaksi->jenis_transaksi === "Penyewaan Mobil Tanpa Driver") {
            $tempIdDriver = 7;
            $tempTarifDriver = 0;
        } else if ($detail_transaksi->jenis_transaksi === "Penyewaan Mobil + Driver" && $request->id_driver_increment !== 7) {
            $tempIdDriver = $detail_transaksi->id_driver_increment;
            $tempTarifDriver = $cariDriver->tarif_driver_harian;
        }

        if ($totalJamDenda >= 3 || $totalHariDenda >= 1) {
            $totalDenda = ($tempHargaSewa + $tempTarifDriver) * ($totalHariDenda + 1);
        } else {
            $totalDenda = 0;
        }

        $promo = $transaksi->id_promo;
        if (!is_null($promo)) {
            $id_promo = Promo_10144::where('id_promo', $promo)->first();
            $tempDiskon = $id_promo->diskon;
            $totalDiskon = 1 - ($tempDiskon / 100);

            $kalkulasiPembayaran = (((($tempHargaSewa * $totalHari) + ($tempTarifDriver * $totalHari)) * $totalDiskon) + ($totalDenda));
        } else {
            $kalkulasiPembayaran = ((($tempHargaSewa * $totalHari) + ($tempTarifDriver * $totalHari)) + ($totalDenda));
        }

        $tempBiayaMobil = ($tempHargaSewa * $totalHari);
        $tempBiayaDriver = ($tempTarifDriver * $totalHari);
        $tempBiayaMobilDriver = $tempBiayaMobil + $tempBiayaDriver;
        $tempTotalDiskon = $tempBiayaMobilDriver * ($tempDiskon / 100);

        $detail_transaksi->diskon_pembayaran = $tempTotalDiskon;
        $detail_transaksi->biaya_mobil_driver = $tempBiayaMobilDriver;
        $detail_transaksi->biaya_mobil = $tempBiayaMobil;
        $detail_transaksi->biaya_driver = $tempBiayaDriver;

        $detail_transaksi->durasi = $totalHari;
        $detail_transaksi->jumlah_pembayaran = $kalkulasiPembayaran;
        $detail_transaksi->denda = $totalDenda;
        $detail_transaksi->tanggal_pengembalian = $updateData['tanggal_pengembalian'];
        $detail_transaksi->status_transaksi = $updateData['status_transaksi'];



        if ($detail_transaksi->save()) {
            return response([
                'message' => 'Update Detail transaksi Success',
                'data' => $detail_transaksi,
            ], 200);
        } // return data Detail transaksi yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update Detail transaksi Failed',
            'data' => null,
        ], 400); // return message saat Detail transaksi gagal diedit
    }

// ======================

    public function updateRating(Request $request, $id_detailTrs_increment)
    {
        $detail_transaksi = Detail_Transaksi_Mobil_10144::where('id_detailTrs_increment', $id_detailTrs_increment)->first();

        if ($detail_transaksi->status_transaksi === 'Transaksi Selesai') {
            if (is_null($detail_transaksi)) {
                return response([
                    'message' => 'Detail transaksi Not Found',
                    'data' => null,
                ], 404);
            } // return message saat data Detail transaksi tidak ditemukan

            $updateData = $request->all(); // mengambil semua input dari api client

            $validate = Validator::make($updateData, [
                'rating_driver' => 'nullable',
            ]); // membuat rule validasi input

            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }
            // return error invalid input

            if ($detail_transaksi->id_driver_increment === 7) {
                $tempRatingDriver = null;
            } else if ($request->id_driver_increment !== 7) {
                $tempRatingDriver = $updateData['rating_driver'];
            }

            $detail_transaksi->rating_driver = $tempRatingDriver;

            if ($detail_transaksi->save()) {
                return response([
                    'message' => 'Update Detail transaksi Success',
                    'data' => $detail_transaksi,
                ], 200);
            } // return data Detail transaksi yang telah diedit dalam bentuk json
            return response([
                'message' => 'Update Detail transaksi Failed',
                'data' => null,
            ], 400); // return message saat Detail transaksi gagal diedit
        } else if ($detail_transaksi->status_transaksi !== 'Sudah Terverifikasi') {
            return response([
                'message' => 'Anda belum terverifikasi',
                'data' => null,
            ], 404);
        } else if (is_null($detail_transaksi->tanggal_pengembalian)) {
            return response([
                'message' => 'Anda belum dapat memberi rating',
                'data' => null,
            ], 404);
        }
    }

    public function updateDetailByCustomer(Request $request, $id_detailTrs_increment)
    {
        $detail_transaksi = Detail_Transaksi_Mobil_10144::where('id_detailTrs_increment', $id_detailTrs_increment)->first();

        if ($detail_transaksi->status_transaksi === 'Sudah Terverifikasi') {
            return response([
                'message' => 'Anda sudah terverifikasi',
                'data' => null,
            ], 404);
        } else if ($detail_transaksi->status_transaksi === 'Transaksi Selesai') {
            return response([
                'message' => 'Transaksi sudah selesai',
                'data' => null,
            ], 404);
        } else {
            if (is_null($detail_transaksi)) {
                return response([
                    'message' => 'Detail transaksi Not Found',
                    'data' => null,
                ], 404);
            } // return message saat data Detail transaksi tidak ditemukan

            $updateData = $request->all(); // mengambil semua input dari api client
            $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment', $request->id_transaksi_increment)->first();
            $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil', $request->id_aset_mobil)->first();
            $detail_transaksi->tanggal_waktu_mulaiSewa = Carbon::parse($updateData['tanggal_waktu_mulaiSewa'])->format('YYYY-MM-DDThh:mm');
            $detail_transaksi->tanggal_waktu_selesaiSewa = Carbon::parse($updateData['tanggal_waktu_selesaiSewa'])->format('YYYY-MM-DDThh:mm');

            $validate = Validator::make($updateData, [
                'tanggal_waktu_mulaiSewa' => 'required',
                'tanggal_waktu_selesaiSewa' => 'required|after_or_equal:tanggal_waktu_mulaiSewa',
                'bukti_transfer' => 'nullable',
                'bukti_transfer.*' => 'max:1024|mimes:jpg,png,jpeg|image',
                'rating_driver' => 'nullable',
                'id_driver_increment' => 'nullable',
                'id_transaksi_increment' => 'required',
                'id_aset_mobil' => 'required',
            ]); // membuat rule validasi input

            if ($validate->fails()) {
                return response(['message' => $validate->errors()], 400);
            }
            // return error invalid input

            $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil', $request->id_aset_mobil)->first();
            $tempHargaSewa = $aset_mobil->harga_sewa_mobil;

            $tglMulai = $request->tanggal_waktu_mulaiSewa;
            $tglAkhir = $request->tanggal_waktu_selesaiSewa;
            $dateTime1 = new DateTime($tglMulai);
            $dateTime2 = new DateTime($tglAkhir);

            $selisihHari = $dateTime1->diff($dateTime2);
            $totalHari = $selisihHari->format('%a');
            $cariDriver = Driver_10144::where('id_driver_increment', $detail_transaksi->id_driver_increment)->first();

            if (!is_null($detail_transaksi->id_driver_increment) || $detail_transaksi->id_driver_increment > 0) {
                $tempTarifDriver = $cariDriver->tarif_driver_harian;
            } else {
                $tempTarifDriver = 0;
            }
            $tempMetodePembayaran = $transaksi->metode_pembayaran;

            if ($tempMetodePembayaran === 'Transfer') {
                if (isset($request->bukti_transfer)) {
                    $uploadBuktiPembayaran = $request->bukti_transfer->store('img_bukti', ['disk' => 'public']);
                    $detail_transaksi->bukti_transfer = $uploadBuktiPembayaran;
                }
            } else if ($tempMetodePembayaran === 'Cash') {
                $uploadBuktiPembayaran = null;
                $detail_transaksi->bukti_transfer = $uploadBuktiPembayaran;
            }

            $promo = $transaksi->id_promo;
            if (!is_null($promo)) {
                $id_promo = Promo_10144::where('id_promo', $promo)->first();
                $tempDiskon = $id_promo->diskon;
                $totalDiskon = 1 - ($tempDiskon / 100);

                $kalkulasiPembayaran = (((($tempHargaSewa * $totalHari) + ($tempTarifDriver * $totalHari)) * $totalDiskon));
            } else {
                $kalkulasiPembayaran = ((($tempHargaSewa * $totalHari) + ($tempTarifDriver * $totalHari)));
            }

            if ($detail_transaksi->id_driver_increment === 7) {
                $tempRatingDriver = null;
            } else if ($request->id_driver_increment !== 7) {
                $tempRatingDriver = $updateData['rating_driver'];
            }

            $tempBiayaMobil = ($tempHargaSewa * $totalHari);
            $tempBiayaDriver = ($tempTarifDriver * $totalHari);
            $tempBiayaMobilDriver = $tempBiayaMobil + $tempBiayaDriver;
            $tempTotalDiskon = $tempBiayaMobilDriver * ($tempDiskon / 100);

            $detail_transaksi->tanggal_waktu_mulaiSewa = $updateData['tanggal_waktu_mulaiSewa'];
            $detail_transaksi->tanggal_waktu_selesaiSewa = $updateData['tanggal_waktu_selesaiSewa'];
            $detail_transaksi->id_aset_mobil = $updateData['id_aset_mobil'];
            $detail_transaksi->biaya_mobil = $tempBiayaMobil;
            $detail_transaksi->biaya_driver = $tempBiayaDriver;
            $detail_transaksi->diskon_pembayaran = $tempTotalDiskon;
            $detail_transaksi->biaya_mobil_driver = $tempBiayaMobilDriver;
            $detail_transaksi->durasi = $totalHari;
            $detail_transaksi->jumlah_pembayaran = $kalkulasiPembayaran;
            $detail_transaksi->rating_driver = $tempRatingDriver;

            if ($detail_transaksi->save()) {
                return response([
                    'message' => 'Update Detail transaksi Success',
                    'data' => $detail_transaksi,
                ], 200);
            } // return data Detail transaksi yang telah diedit dalam bentuk json
            return response([
                'message' => 'Update Detail transaksi Failed',
                'data' => null,
            ], 400); // return message saat Detail transaksi gagal diedit
        }
    }

}