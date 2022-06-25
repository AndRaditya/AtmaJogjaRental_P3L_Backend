<?php

namespace App\Models;

use App\Models\Customer_10144;
use App\Models\Pegawai_10144;
use App\Models\Promo_10144;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// use App\Models\Detail_Transaksi_Mobil_10144;

class Transaksi_Mobil_10144 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_transaksi_increment';
    protected $fillable = [
        'id_transaksi_increment', 'id_transaksi_mobil',
        'id_customer_increment', 'id_pegawai',
        'id_promo', 'tanggal_transaksi', 'metode_pembayaran',
    ];

    public function Transaksi_Promo()
    {
        return $this->belongsTo(Promo_10144::class, 'id_promo', 'id_promo');
    }

    public function Transaksi_Customer()
    {
        return $this->belongsTo(Customer_10144::class, 'id_customer_increment', 'id_customer_increment');
    }

    public function Transaksi_Pegawai()
    {
        return $this->belongsTo(Pegawai_10144::class, 'id_pegawai', 'id_pegawai');
    }

    // public function Transaksi_detailTransaksi()
    // {
    //     return $this->belongsTo(Detail_Transaksi_Mobil_10144::class, 'id_detail_transaksi_mobil', 'id_detail_transaksi_mobil');
    // }

    public function getCreatedAtAttribute()
    {
        if (!is_null($this->attributes['created_at'])) {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute()
    {
        if (!is_null($this->attributes['updated_at'])) {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}