<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Detail_Transaksi_Mobil_10144;
// use DB;
use App\Models\Aset_Mobil_10144;
use DateTime;
use App\Models\Transaksi_Mobil_10144;
use App\Models\Promo_10144;
use App\Models\Driver_10144;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DetailTransaksiController extends Controller
{
    // public function index()
    // {
    //     $detail_transaksis = Detail_Transaksi_Mobil_10144::all(); // mengambil semua data detail_transaksis

    //     if(count($detail_transaksis) > 0)
    //     {
    //         return response([
    //             'message' => 'Retrieve All Success',
    //             'data' => $detail_transaksis
    //         ], 200);
    //     } // return data semua detail_transaksis dalam bentuk json

    //     return response([
    //         'message' => 'Empty',
    //         'data' => null
    //     ], 400); // return message data detail_transaksis kosong
    // }

    public function index()
    {
        $detail_transaksis = DB::table('detail__transaksi__mobil_10144s')
                            ->join('driver_10144s', 'detail__transaksi__mobil_10144s.id_driver_increment', '=', 'driver_10144s.id_driver_increment')
                            ->join('aset__mobil_10144s', 'detail__transaksi__mobil_10144s.id_aset_mobil', '=', 'aset__mobil_10144s.id_aset_mobil')
                            ->join('transaksi__mobil_10144s', 'detail__transaksi__mobil_10144s.id_transaksi_increment', '=', 'transaksi__mobil_10144s.id_transaksi_increment')
                            ->select('detail__transaksi__mobil_10144s.*', 'transaksi__mobil_10144s.id_transaksi_mobil', 
                            'transaksi__mobil_10144s.tanggal_transaksi', 'aset__mobil_10144s.nama_mobil', 'aset__mobil_10144s.plat_nomor_mobil', 
                            'driver_10144s.id_driver', 'driver_10144s.nama_driver')
                            ->get(); // mengambil semua data aset_mobil


        if(count($detail_transaksis) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $detail_transaksis
            ], 200);
        } // return data semua detail_transaksis dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data detail_transaksis kosong
    }


    public function show($id_detailTrs_increment)
    {
        $detail_transaksi = Detail_Transaksi_Mobil_10144::where('id_detailTrs_increment',$id_detailTrs_increment)->first(); // mencari data Detail transaksi berdasarkan id

        if(!is_null($detail_transaksi))
        {
            return response([
                'message' => 'Retrieve detail transaksi Success',
                'data' => $detail_transaksi
            ], 200);
        } // return data Detail transaksi yang ditemukan dalam bentuk json

        return response([
            'message' => 'Detail transaksi Not Found',
            'data' => null
        ], 404); // return message saat data Detail transaksi tidak ditemukan
    }

    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        
        // $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil', $request->id_aset_mobil)->first();

        $storeData['tanggal_waktu_mulaiSewa'] = Carbon::parse($storeData['tanggal_waktu_mulaiSewa'])->format('YYYY-MM-DDThh:mm');
        $storeData['tanggal_waktu_selesaiSewa'] = Carbon::parse($storeData['tanggal_waktu_selesaiSewa'])->format('YYYY-MM-DDThh:mm');
        $storeData['tanggal_pengembalian'] = Carbon::parse($storeData['tanggal_pengembalian'])->format('YYYY-MM-DDThh:mm');

        $validate = Validator::make($storeData, [
            // 'tanggal_waktu_mulaiSewa' => 'required|after_or_equal:' . $transaksi->tanggal_transaksi,
            'tanggal_waktu_mulaiSewa' => 'required',
            'tanggal_waktu_selesaiSewa' => 'required|after_or_equal:tanggal_waktu_mulaiSewa',
            'tanggal_pengembalian' => 'required|after_or_equal:tanggal_waktu_selesaiSewa',
            'rating_driver' => 'nullable',
            'id_driver_increment' => 'nullable',
            'id_transaksi_increment' => 'required',
            'id_aset_mobil' => 'required'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input
            
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment',$request->id_transaksi_increment)->first();
        $checkDetailTransaksi = Detail_Transaksi_Mobil_10144::count();

        $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil', $request->id_aset_mobil)->first();
        $tempHargaSewa = $aset_mobil->harga_sewa_mobil;

        $tglMulai = $request->tanggal_waktu_mulaiSewa;
        $tglAkhir = $request->tanggal_waktu_selesaiSewa;
        $tglPengembalian = $request->tanggal_pengembalian;
        $dateTime1 = new DateTime($tglMulai);
        $dateTime2 = new DateTime($tglAkhir);
        $dateTime3 = new DateTime($tglPengembalian);

        $selisihHari = $dateTime1->diff($dateTime2);
        $totalHari = $selisihHari->format('%a');

        $selisihJam = $dateTime2->diff($dateTime3);
        $totalJam = $selisihJam->format('%h');

        if($totalJam >= 3){
            $totalDenda = $tempHargaSewa;
            // $storeData['denda'] = $totalDenda;
        }
        else if($totalJam < 3){
            $totalDenda = 0;
            // $storeData['denda'] = $totalDenda;
        }

        //generate id transaksi
        if($checkDetailTransaksi == 0){
            if(!is_null($request->id_driver_increment) && $request->id_driver_increment != 7){
                $id_awal = "TRN" . date('ymd') . '01-';
                $id_detailTransaksi_format = $id_awal . sprintf('%03d', 1); 
                // $storeData['id_detail_transaksi_mobil']=$id_detailTransaksi_format;
                $jenisTransaksiTemp = 'Penyewaan Mobil + Driver';

                $cariTarifDriver = Driver_10144::where('id_driver_increment', $request->id_driver_increment)->first();
                $tempTarifDriver = $cariTarifDriver->tarif_driver_harian;
            }else{
                $id_awal = "TRN" . date('ymd') . '00-';
                $id_detailTransaksi_format = $id_awal . sprintf('%03d', 1); 
                // $storeData['id_detail_transaksi_mobil']=$id_detailTransaksi_format;
                $jenisTransaksiTemp = 'Penyewaan Mobil Tanpa Driver';
                $tempTarifDriver = 0;
            }
        }else{
            if(!is_null($request->id_driver_increment) && $request->id_driver_increment != 7){
                $id_awal = "TRN" . date('ymd') . '01-';
                $id_angka = DB::table('detail__transaksi__mobil_10144s')->select('id_detailTrs_increment')->orderBy('id_detailTrs_increment','desc')->first();
                $id_angka_temp = $id_angka->id_detailTrs_increment+1;
                $id_detailTransaksi_format = $id_awal . sprintf('%03d', $id_angka_temp); 
                // $storeData['id_detail_transaksi_mobil']=$id_detailTransaksi_format;
                $jenisTransaksiTemp = 'Penyewaan Mobil + Driver';
                
                $cariTarifDriver = Driver_10144::where('id_driver_increment', $request->id_driver_increment)->first();
                $tempTarifDriver = $cariTarifDriver->tarif_driver_harian;
            }else{
                $id_awal = "TRN" . date('ymd') . '00-';
                $id_angka = DB::table('detail__transaksi__mobil_10144s')->select('id_detailTrs_increment')->orderBy('id_detailTrs_increment','desc')->first();
                $id_angka_temp = $id_angka->id_detailTrs_increment+1;
                $id_detailTransaksi_format = $id_awal . sprintf('%03d', $id_angka_temp); 

                // $storeData['id_detail_transaksi_mobil']=$id_detailTransaksi_format;
                $jenisTransaksiTemp = 'Penyewaan Mobil Tanpa Driver';
                $tempTarifDriver = 0;
            }
        }    
        
        $promo = $transaksi->id_promo;
        if($promo != 8){
            $id_promo = Promo_10144::where('id_promo',$promo)->first();
            if($id_promo != 8 ){
                $tempDiskon = $id_promo->diskon;
                $totalDiskon = 1 - ($tempDiskon / 100);
    
                $kalkulasiPembayaran = (((($tempHargaSewa * $totalHari) + ($tempTarifDriver * $totalHari)) * $totalDiskon) + ($totalDenda));
            }else{
                $kalkulasiPembayaran = ($tempHargaSewa * $totalHari) + ($tempTarifDriver * $totalHari) + ($totalDenda);
            }
            // $storeData['jumlah_pembayaran']=$kalkulasiPembayaran;
        }else{
            $kalkulasiPembayaran = ($tempHargaSewa * $totalHari) + ($tempTarifDriver * $totalHari) + ($totalDenda);
            // $storeData['jumlah_pembayaran']=$kalkulasiPembayaran;
        }

        $cariDriver = Driver_10144::where('id_driver_increment',$request->id_driver_increment)->first();
        if($request->id_driver_increment != 7){
            // $statusDriver = $cariDriver->status_driver;
            $cariDriver->status_driver = 'Sibuk';
        }

        $detail_transaksi = Detail_Transaksi_Mobil_10144::create([
            'tanggal_waktu_mulaiSewa' => $request->tanggal_waktu_mulaiSewa,
            'tanggal_waktu_selesaiSewa' => $request->tanggal_waktu_selesaiSewa,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'rating_driver' => $request->rating_driver,
            'id_driver_increment' => $request->id_driver_increment,
            'id_transaksi_increment' => $request->id_transaksi_increment,
            'id_aset_mobil' => $request->id_aset_mobil,
            'denda' => $totalDenda,
            'jenis_transaksi' => $jenisTransaksiTemp,
            'id_detail_transaksi_mobil' => $id_detailTransaksi_format,
            'jumlah_pembayaran' => $kalkulasiPembayaran,
        ]);
        return response([
            'message' => 'Add Detail transaksi Success',
            'data' => $detail_transaksi
        ], 200); // return data Detail transaksi baru dalam bentuk json
    }

    public function destroy($id_detailTrs_increment)
    {
        $detail_transaksi = Detail_Transaksi_Mobil_10144::where('id_detailTrs_increment',$id_detailTrs_increment); // mencari data Detail transaksi berdasarkan id

        if(is_null($detail_transaksi))
        {
            return response([
                'message' => 'Detail transaksi Not Found',
                'data' => null
            ], 404); 
        } // return message saat data Detail transaksi tidak ditemukan

        if($detail_transaksi->delete())
        {
            return response([
                'message' => 'Delete Detail transaksi Success',
                'data' => $detail_transaksi
            ], 200); 
        } // return message saat berhasil menghapus data Detail transaksi

        return response([
            'message' => 'Delete Detail transaksi Failed',
            'data' => null
        ], 400); // return message saat gagal menghapus data Detail transaksi
    }

    public function update(Request $request, $id_detailTrs_increment)
    {
        $detail_transaksi = Detail_Transaksi_Mobil_10144::where('id_detailTrs_increment',$id_detailTrs_increment)->first();
        if(is_null($detail_transaksi))
        {
            return response([
                'message' => 'Detail transaksi Not Found',
                'data' => null
            ], 404); 
        } // return message saat data Detail transaksi tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment',$request->id_transaksi_increment)->first();
        $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil', $request->id_aset_mobil)->first();

        // $updateData['tanggal_waktu_mulaiSewa']
        $detail_transaksi->tanggal_waktu_mulaiSewa = Carbon::parse($updateData['tanggal_waktu_mulaiSewa'])->format('YYYY-MM-DDThh:mm');
        // $updateData['tanggal_waktu_selesaiSewa']
        $detail_transaksi->tanggal_waktu_selesaiSewa = Carbon::parse($updateData['tanggal_waktu_selesaiSewa'])->format('YYYY-MM-DDThh:mm');
        // $updateData['tanggal_pengembalian'] 
        $detail_transaksi->tanggal_pengembalian= Carbon::parse($updateData['tanggal_pengembalian'])->format('YYYY-MM-DDThh:mm');

        $validate = Validator::make($updateData, [
            'tanggal_waktu_mulaiSewa' => 'required',
            'tanggal_waktu_selesaiSewa' => 'required|after_or_equal:tanggal_waktu_mulaiSewa',
            'tanggal_pengembalian' => 'required|after_or_equal:tanggal_waktu_selesaiSewa',
            'rating_driver' => 'nullable',
            'id_driver_increment' => 'nullable',
            'id_transaksi_increment' => 'required',
            'id_aset_mobil' => 'required'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $aset_mobil = Aset_Mobil_10144::where('id_aset_mobil', $request->id_aset_mobil)->first();
        $tempHargaSewa = $aset_mobil->harga_sewa_mobil;

        $tglMulai = $request->tanggal_waktu_mulaiSewa;
        $tglAkhir = $request->tanggal_waktu_selesaiSewa;
        $tglPengembalian = $request->tanggal_pengembalian;
        $dateTime1 = new DateTime($tglMulai);
        $dateTime2 = new DateTime($tglAkhir);
        $dateTime3 = new DateTime($tglPengembalian);

        $selisihHari = $dateTime1->diff($dateTime2);
        $totalHari = $selisihHari->format('%a');

        $selisihJam = $dateTime2->diff($dateTime3);
        $totalJam = $selisihJam->format('%h');
        $cariDriver = Driver_10144::where('id_driver_increment', $detail_transaksi->id_driver_increment)->first();

        if(!is_null($detail_transaksi->id_driver_increment) || $detail_transaksi->id_driver_increment > 0){
            $tempTarifDriver = $cariDriver->tarif_driver_harian;
        }else{
            $tempTarifDriver = 0;
        }

        if($totalJam >= 3){
            $totalDenda = $tempHargaSewa;
        }
        else if($totalJam < 3){
            $totalDenda = 0;
        }

        $promo = $transaksi->id_promo;
        if(!is_null($promo)){
            $id_promo = Promo_10144::where('id_promo',$promo)->first();
            $tempDiskon = $id_promo->diskon;
            $totalDiskon = 1 - ($tempDiskon / 100);

            $kalkulasiPembayaran = (((($tempHargaSewa * $totalHari) + ($tempTarifDriver * $totalHari)) * $totalDiskon) + ($totalDenda));
        }else{
            $kalkulasiPembayaran = ((($tempHargaSewa * $totalHari) + ($tempTarifDriver * $totalHari)) + ($totalDenda));
        }

        $detail_transaksi->jumlah_pembayaran = $kalkulasiPembayaran;  
        $detail_transaksi->denda = $totalDenda;  
        $detail_transaksi->tanggal_pengembalian = $updateData['tanggal_pengembalian'];  
        $detail_transaksi->tanggal_waktu_mulaiSewa = $updateData['tanggal_waktu_mulaiSewa'];  
        $detail_transaksi->tanggal_waktu_selesaiSewa = $updateData['tanggal_waktu_selesaiSewa'];
        $detail_transaksi->rating_driver = $updateData['rating_driver'];
            
        if($detail_transaksi->save())
        {
            return response([
                'message' => 'Update Detail transaksi Success',
                'data' => $detail_transaksi
            ], 200);
        } // return data Detail transaksi yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update Detail transaksi Failed',
            'data' => null
        ], 400); // return message saat Detail transaksi gagal diedit
    }

}
