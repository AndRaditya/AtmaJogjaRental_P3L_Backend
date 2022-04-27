<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaksi_Mobil_10144;
use App\Models\Pegawai_10144;
use App\Models\Customer_10144;
use App\Models\Promo_10144;

use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // public function index()
    // {
    //     $transaksis = Transaksi_Mobil_10144::all(); // mengambil semua data transaksis
    //     // $transaksis = Transaksi_Mobil_10144::with(['Transaksi_Promo','Transaksi_Customer',
    //     // 'Transaksi_Pegawai'])->get(); 

    //     if(count($transaksis) > 0)
    //     {
    //         return response([
    //             'message' => 'Retrieve All Success',
    //             'data' => $transaksis
    //         ], 200);
    //     } // return data semua transaksis dalam bentuk json

    //     return response([
    //         'message' => 'Empty',
    //         'data' => null
    //     ], 400); // return message data transaksis kosong
    // }

    public function index()
    {
        $transaksis = DB::table('transaksi__mobil_10144s')
                        ->join('promo_10144s', 'transaksi__mobil_10144s.id_promo', '=', 'promo_10144s.id_promo')
                        ->join('pegawai_10144s', 'transaksi__mobil_10144s.id_pegawai', '=', 'pegawai_10144s.id_pegawai')
                        ->join('customer_10144s', 'transaksi__mobil_10144s.id_customer_increment', '=', 'customer_10144s.id_customer_increment')
                        ->select('transaksi__mobil_10144s.*', 'pegawai_10144s.nama_pegawai', 'promo_10144s.id_promo',
                        'promo_10144s.kode_promo', 'promo_10144s.status_promo', 'promo_10144s.diskon', 
                        'customer_10144s.nama_customer', 'customer_10144s.id_customer')
                        ->get(); // mengambil semua data aset_mobil

        if(count($transaksis) > 0)
        {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $transaksis
            ], 200);
        } // return data semua transaksis dalam bentuk json

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400); // return message data transaksis kosong
    }


    public function show($id_transaksi_increment)
    {
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment',$id_transaksi_increment)->first(); // mencari data transaksi berdasarkan id
        // $transaksi = Transaksi_Mobil_10144::with(['Transaksi_Promo','Transaksi_Customer',
        // 'Transaksi_Pegawai'])->first(); 

        if(!is_null($transaksi))
        {
            return response([
                'message' => 'Retrieve transaksi Success',
                'data' => $transaksi
            ], 200);
        } // return data transaksi yang ditemukan dalam bentuk json

        return response([
            'message' => 'transaksi Not Found',
            'data' => null
        ], 404); // return message saat data transaksi tidak ditemukan
    }

    public function showByCustomer($id_customer_increment)
    {
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment',$id_transaksi_increment); // mencari data transaksi berdasarkan id
        // $transaksi = Transaksi_Mobil_10144::with(['Transaksi_Promo','Transaksi_Customer',
        // 'Transaksi_Pegawai'])->first(); 

        if(!is_null($transaksi))
        {
            return response([
                'message' => 'Retrieve transaksi Success',
                'data' => $transaksi
            ], 200);
        } // return data transaksi yang ditemukan dalam bentuk json

        return response([
            'message' => 'transaksi Not Found',
            'data' => null
        ], 404); // return message saat data transaksi tidak ditemukan
    }


    public function store(Request $request)
    {
        $storeData = $request->all(); // mengambil semua input dari api client
        // $buktiTransferValidator = $request->bukti_transfer ? 'max:1024|mimes:jpg,png,jpeg|image' : '';
        $validate = Validator::make($storeData, [
            // 'status_transaksi' => 'required|max:30',
            'bukti_transfer' => 'nullable',
            'bukti_transfer.*' => 'max:1024|mimes:jpg,png,jpeg|image',
            // 'bukti_transfer' => $buktiTransferValidator,
            'tanggal_transaksi' => 'required|date_format:Y-m-d',
            'metode_pembayaran' => 'required|max:20',
            'id_customer_increment' => 'required',
            'id_pegawai' => 'required',
            'id_promo' => 'nullable'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $tempMetodePembayaran = $request->metode_pembayaran;
        // $cekPromoInputan = $request->id_promo;
        
        if(empty($request->id_promo)){
            // $statusPromo = null;
            $tempId_promo = null;
        }else if(!empty($request->id_promo)){
            $cariId_promo = Promo_10144::where('id_promo',$request->id_promo)->first();
            $statusPromo = $cariId_promo->status_promo;
            if($statusPromo === 'Aktif'){
                $tempId_promo = $request->id_promo;
            }else{
                $tempId_promo = null;
            }
        }
        $checkTransaksi = Transaksi_Mobil_10144::count();
        
        //generate id transaksi
        if($checkTransaksi == 0){
            $id_awal = "NOTA" . date('ymd')."00-";
            $id_transaksi_format = $id_awal . sprintf('%03d', 1); 
        }else{
            $id_awal = "NOTA" . date('ymd')."00-";
            $id_angka = DB::table('transaksi__mobil_10144s')->select('id_transaksi_increment')->orderBy('id_transaksi_increment','desc')->first();
            $id_angka_temp = $id_angka->id_transaksi_increment+1;
            $id_transaksi_format = $id_awal . sprintf('%03d', $id_angka_temp); 
        }

        if($tempMetodePembayaran == 'Transfer'){
            $cekBukti = $request->bukti_transfer;
            // $uploadBuktiPembayaran = $request->bukti_transfer->store('img_bukti', ['disk' => 'public']);
            // $tempStatusTransaksi = 'Sudah lunas';       
            if(!empty($cekBukti)){
                $uploadBuktiPembayaran = $request->bukti_transfer->store('img_bukti', ['disk' => 'public']);
                $tempStatusTransaksi = 'Sudah lunas';
            }
            // else{
            //     $uploadBuktiPembayaran = NULL;
            //     $tempStatusTransaksi = 'Belum lunas';
            // }
        }else if($tempMetodePembayaran == 'Cash'){
            $uploadBuktiPembayaran = null;
            $tempStatusTransaksi = 'Pembayaran di tempat';
        }

        $transaksi = Transaksi_Mobil_10144::create([
            'status_transaksi' => $tempStatusTransaksi,
            'bukti_transfer' => $uploadBuktiPembayaran,
            'tanggal_transaksi' => $request->tanggal_transaksi,
            'metode_pembayaran' => $request->metode_pembayaran,
            'id_promo' => $request->id_promo,
            'id_transaksi_mobil' => $id_transaksi_format,
            'id_customer_increment' => $request->id_customer_increment,
            'id_pegawai' => $request->id_pegawai
        ]);
        return response([
            'message' => 'Add transaksi Success',
            'data' => $transaksi
        ], 200); // return data transaksi baru dalam bentuk json
    }

    public function destroy($id_transaksi_increment)
    {
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment',$id_transaksi_increment); // mencari data transaksi berdasarkan id

        if(is_null($transaksi))
        {
            return response([
                'message' => 'transaksi Not Found',
                'data' => null
            ], 404); 
        } // return message saat data transaksi tidak ditemukan

        if($transaksi->delete())
        {
            return response([
                'message' => 'Delete transaksi Success',
                'data' => $transaksi
            ], 200); 
        } // return message saat berhasil menghapus data transaksi

        return response([
            'message' => 'Delete transaksi Failed',
            'data' => null
        ], 400); // return message saat gagal menghapus data transaksi
    }

    public function update(Request $request, $id_transaksi_increment)
    {
        $transaksi = Transaksi_Mobil_10144::where('id_transaksi_increment',$id_transaksi_increment)->first();
        if(is_null($transaksi))
        {
            return response([
                'message' => 'transaksi Not Found',
                'data' => null
            ], 404); 
        } // return message saat data transaksi tidak ditemukan

        $updateData = $request->all(); // mengambil semua input dari api client
        $validate = Validator::make($updateData, [
            // 'status_transaksi' => 'required|max:30',
            // 'bukti_transfer' => 'nullable',
            // 'bukti_transfer.*' => 'max:1024|mimes:jpg,png,jpeg|image',
            // 'bukti_transfer' => $buktiTransferValidator,
            // 'tanggal_transaksi' => 'required|date_format:Y-m-d',
            // 'metode_pembayaran' => 'required|max:20',
            // 'id_customer_increment' => 'required',
            // 'id_pegawai' => 'required',
            // 'id_promo' => 'nullable'
        ]); // membuat rule validasi input

        if($validate->fails())
            return response(['message' => $validate->errors()], 400); // return error invalid input

        $transaksi->tanggal_transaksi = $updateData['tanggal_transaksi'];
        $transaksi->metode_pembayaran = $updateData['metode_pembayaran'];
        $transaksi->id_customer_increment = $updateData['id_customer_increment'];
        $transaksi->id_pegawai = $updateData['id_pegawai'];

        $tempMetodePembayaran = $request->metode_pembayaran;
        $transaksi->tanggal_transaksi = $updateData['tanggal_transaksi'];

        if($tempMetodePembayaran == 'Transfer'){
            // $cekBukti = $request->bukti_transfer;
    
            // if(isset($request->bukti_transfer)){
            //     $uploadBuktiPembayaran = $request->bukti_transfer->store('img_bukti', ['disk' => 'public']);
            //     $transaksi->bukti_transfer = $uploadBuktiPembayaran;
            //     $tempStatusTransaksi = "Sudah lunas";
            // }
            if(!empty($cekBukti)){
                $uploadBuktiPembayaran = $request->bukti_transfer->store('img_bukti', ['disk' => 'public']);
                $transaksi->bukti_transfer = $uploadBuktiPembayaran;
                $transaksi->status_transaksi = 'Sudah lunas';
            }

        }else if($tempMetodePembayaran == 'Cash'){
            $uploadBuktiPembayaran = null;
            $transaksi->status_transaksi = 'Pembayaran di tempat';
        }   

        if($transaksi->save())
        {
            return response([
                'message' => 'Update transaksi Success',
                'data' => $transaksi
            ], 200);
        } // return data transaksi yang telah diedit dalam bentuk json
        return response([
            'message' => 'Update transaksi Failed',
            'data' => null
        ], 400); // return message saat transaksi gagal diedit
    }

}
