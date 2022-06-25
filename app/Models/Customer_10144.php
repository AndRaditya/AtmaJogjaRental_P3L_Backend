<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer_10144 extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_customer_increment';
    protected $fillable = [
        'id_customer_increment', 'id_customer', 'nama_customer', 'jenis_kelamin_customer', 'nomor_telepon_customer', 'alamat_customer',
        'email_customer', 'tanggal_lahir_customer', 'no_sim_customer', 'no_ktp_customer',
        'password_customer', 'status_dokumen', 'foto_ktp_customer', 'foto_sim_customer', 'umur_customer'
    ];

    public function getCreatedAtAttribute()
    {
        if (!is_null($this->attributes['created_at'])) {
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAsttribute()
    {
        if (!is_null($this->attributes['updated_at'])) {
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
}