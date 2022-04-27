<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Detail_Jadwal_10144;
use App\Models\Jabatan_Pegawai_10144;

class Pegawai_10144 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_pegawai';
    protected $fillable = [
        'id_pegawai', 'id_jabatan', 'nama_pegawai',
        'alamat_pegawai', 'tanggal_lahir_pegawai', 'jenis_kelamin_pegawai',
        'email_pegawai', 'nomor_telepon_pegawai', 'password_pegawai', 'foto_pegawai'
    ];

    public function Pegawai_Jabatan()
    {
        return $this->belongsTo(Jabatan_Pegawai_10144::class, 'id_jabatan', 'id_jabatan');
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
