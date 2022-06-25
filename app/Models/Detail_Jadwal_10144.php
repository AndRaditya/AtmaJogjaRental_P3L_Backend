<?php

namespace App\Models;

use App\Models\Jadwal_Pegawai_10144;
use App\Models\Pegawai_10144;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Jadwal_10144 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_detail_jadwal';
    protected $fillable = [
        'id_detail_jadwal', 'id_pegawai', 'id_jadwal_increment', 'keterangan_jadwal',
    ];

    public function DetailJadwal_Pegawai()
    {
        return $this->belongsTo(Pegawai_10144::class, 'id_pegawai', 'id_pegawai');
    }

    public function DetailJadwal_Jadwal()
    {
        return $this->belongsTo(Jadwal_Pegawai_10144::class, 'id_jadwal_increment', 'id_jadwal_increment');
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