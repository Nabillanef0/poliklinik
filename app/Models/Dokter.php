<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'alamat', 'no_hp', 'id_poli'];

    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    public function jadwalPeriksa()
    {
        return $this->hasMany(JadwalPeriksa::class, 'id_dokter');
    }

    public function daftarPoli()
    {
        return $this->hasManyThrough(DaftarPoli::class, JadwalPeriksa::class, 'id_dokter', 'id_jadwal', 'id', 'id');
    }

    public function periksas()
    {
        return $this->hasMany(Periksa::class, 'dokter', 'id');
    }

}
