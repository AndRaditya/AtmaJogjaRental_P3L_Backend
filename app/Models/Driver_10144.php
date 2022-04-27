<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Driver_10144 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_driver_increment';
    protected $fillable = [
        'id_driver_increment','id_driver', 'nama_driver', 'nomor_telepon_driver',
        'alamat_driver','email_driver','tanggal_lahir_driver', 'jenis_kelamin_driver', 
        'bahasa_driver', 'foto_driver', 'tarif_driver_harian', 'status_driver', 'password_driver','rerata_rating',
        'foto_sim', 'surat_napza', 'surat_kesehatan_jiwa', 'surat_kesehatan_jasmani', 'skck'
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
    