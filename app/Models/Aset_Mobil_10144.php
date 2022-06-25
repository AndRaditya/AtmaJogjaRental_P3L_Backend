<?php

namespace App\Models;

use App\Models\Pemilik_Mobil_10144;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset_Mobil_10144 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_aset_mobil';
    protected $fillable = [
        'id_aset_mobil', 'id_pemilik_mobil', 'plat_nomor_mobil', 'nama_mobil', 'tipe_mobil', 'jenis_transmisi_mobil',
        'jenis_bahanbakar_mobil', 'volume_bahanbakar_mobil', 'warna_mobil',
        'kapasitas_penumpang_mobil', 'fasilitas_mobil', 'nomor_stnk_mobil',
        'harga_sewa_mobil', 'volume_bagasi_mobil', 'foto_mobil', 'status_mobil',
    ];

    public function asetMobil_Pemilik()
    {
        return $this->belongsTo(Pemilik_Mobil_10144::class, 'id_pemilik_mobil', 'id_pemilik_mobil');
    }

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