<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Aset_Mobil_10144;

class Pemilik_Mobil_10144 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_pemilik_mobil';
    protected $fillable = [
        'id_pemilik_mobil', 'nama_pemilik_mobil', 'no_ktp_pemilik_mobil',
        'alamat_pemilik_mobil', 'nomor_telepon_pemilik_mobil', 'periode_kontrak_mulai_mobil',
        'periode_kontrak_akhir_mobil', 'tanggal_terakhir_servis_mobil'
    ];

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
