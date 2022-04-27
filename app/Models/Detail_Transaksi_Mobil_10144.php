<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Aset_Mobil_10144;
use App\Models\Driver_10144;
use App\Models\Transaksi_Mobil_10144;

class Detail_Transaksi_Mobil_10144 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_detailTrs_increment';
    protected $fillable = [
        'id_detailTrs_increment','id_detail_transaksi_mobil', 'id_aset_mobil', 'id_driver_increment',
        'id_transaksi_increment', 'tanggal_pengembalian',
        'tanggal_waktu_mulaiSewa', 'tanggal_waktu_selesaiSewa', 'rating_driver', 
        'jenis_transaksi', 'jumlah_pembayaran', 'denda'
    ];

    public function detailTransaksi_asetMobil()
    {
        return $this->belongsTo(Aset_Mobil_10144::class, 'id_aset_mobil', 'id_aset_mobil');
    }

    public function detailTransaksi_driver()
    {
        return $this->belongsTo(Driver_10144::class, 'id_driver_increment', 'id_driver_increment');
    }

    public function detailTransaksi_transaksi()
    {
        return $this->belongsTo(Transaksi_Mobil_10144::class, 'id_transaksi_increment', 'id_transaksi_increment');
    }

    public function getCreatedAtAttribute()
    {
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute()
    {
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}
