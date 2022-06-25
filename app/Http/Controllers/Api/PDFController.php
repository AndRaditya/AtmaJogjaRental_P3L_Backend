<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PDFController extends Controller
{
    //
    public function cariLaporanPendapatan($tahun, $bulan)
    {
        $laporan_pendapatan = DB::select("SELECT CUS.nama_customer, M.nama_mobil, DET.jenis_transaksi, count(CUS.id_customer) AS Jumlah_Transaksi,SUM(DET.jumlah_pembayaran) AS Jumlah_Pendapatan
                                FROM customer_10144s CUS JOIN transaksi__mobil_10144s TRS ON (CUS.id_customer_increment = TRS.id_customer_increment) JOIN detail__transaksi__mobil_10144s DET ON (TRS.id_transaksi_increment = DET.id_transaksi_increment)
                                JOIN aset__mobil_10144s M ON (DET.id_aset_mobil = M.id_aset_mobil) WHERE (SELECT YEAR(DET.tanggal_waktu_mulaiSewa)) = $tahun
                                AND (SELECT MONTH(DET.tanggal_waktu_mulaiSewa)) = $bulan GROUP BY DET.jenis_transaksi, CUS.id_customer");

        if (count($laporan_pendapatan) > 0) {
            return response([
                'message' => 'Success',
                'data' => $laporan_pendapatan,
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => $laporan_pendapatan,
        ], 400);
    }

    public function cariLaporanPenyewaan($tahun, $bulan)
    {
        $laporan_penyewaan = DB::select("SELECT M.tipe_mobil, M.nama_mobil, count(DET.id_aset_mobil) AS Jumlah_Peminjaman,SUM(DET.jumlah_pembayaran) AS Pendapatan
                                FROM detail__transaksi__mobil_10144s DET JOIN aset__mobil_10144s M ON (DET.id_aset_mobil = M.id_aset_mobil)
                                WHERE (SELECT YEAR(DET.tanggal_waktu_mulaiSewa)) = $tahun
                                AND (SELECT MONTH(DET.tanggal_waktu_mulaiSewa)) = $bulan
                                GROUP BY DET.id_aset_mobil, M.tipe_mobil
                                ORDER BY count(DET.id_aset_mobil) DESC");

        if (count($laporan_penyewaan) > 0) {
            return response([
                'message' => 'Success',
                'data' => $laporan_penyewaan,
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => $laporan_penyewaan,
        ], 400);
    }

    public function cariLaporanTop5Driver($tahun, $bulan)
    {
        $laporan_top5Driver = DB::select("SELECT D.id_driver, D.nama_driver, count(DET.id_driver_increment) AS Jumlah_Transaksi
                                FROM detail__transaksi__mobil_10144s DET JOIN driver_10144s D ON (DET.id_driver_increment = D.id_driver_increment)
                                WHERE (SELECT YEAR(DET.tanggal_waktu_mulaiSewa)) = $tahun
                                AND (SELECT MONTH(DET.tanggal_waktu_mulaiSewa)) = $bulan
                                AND NOT (D.id_driver_increment = '07')
                                GROUP BY D.id_driver
                                ORDER BY count(DET.id_driver_increment) DESC
                                LIMIT 5");

        if (count($laporan_top5Driver) > 0) {
            return response([
                'message' => 'Success',
                'data' => $laporan_top5Driver,
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => $laporan_top5Driver,
        ], 400);
    }

    public function cariLaporanTop5Customer($tahun, $bulan)
    {
        $laporan_top5Customer = DB::select("SELECT CUS.nama_customer , COUNT(TRS.id_transaksi_mobil) AS Jumlah_Transaksi
                                    FROM customer_10144s CUS JOIN transaksi__mobil_10144s TRS ON(CUS.id_customer_increment = TRS.id_customer_increment)
                                    WHERE YEAR(TRS.tanggal_transaksi)=$tahun
                                    AND MONTH(TRS.tanggal_transaksi)=$bulan
                                    GROUP BY CUS.id_customer_increment
                                    ORDER BY COUNT(TRS.id_transaksi_mobil)
                                    DESC LIMIT 5");

        if (count($laporan_top5Customer) > 0) {
            return response([
                'message' => 'Success',
                'data' => $laporan_top5Customer,
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => $laporan_top5Customer,
        ], 400);
    }

    public function cariLaporanPerformaDriver($tahun, $bulan)
    {
        $laporan_performaDriver = DB::select("SELECT D.id_driver, D.nama_driver, count(DET.id_driver_increment) AS Jumlah_Transaksi, D.rerata_rating
                                        FROM detail__transaksi__mobil_10144s DET JOIN driver_10144s D ON (DET.id_driver_increment = D.id_driver_increment)
                                        WHERE (SELECT YEAR(DET.tanggal_waktu_mulaiSewa)) = $tahun
                                        AND (SELECT MONTH(DET.tanggal_waktu_mulaiSewa)) = $bulan
                                        AND NOT (D.id_driver_increment = '07')
                                        GROUP BY D.id_driver, D.nama_driver
                                        ORDER BY count(DET.id_driver_increment) DESC");

        if (count($laporan_performaDriver) > 0) {
            return response([
                'message' => 'Success',
                'data' => $laporan_performaDriver,
            ], 200);
        }
        return response([
            'message' => 'Empty',
            'data' => $laporan_performaDriver,
        ], 400);
    }

}