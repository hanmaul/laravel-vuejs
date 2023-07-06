<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswas';

    protected $fillable = [
        'npm',
        'nama',
        'jenis_kelamin',
        'fakultas_id',
        'prodi_id',
        'alamat',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id','id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id','id');
    }
}
