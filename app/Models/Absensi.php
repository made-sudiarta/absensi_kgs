<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Absensi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'karyawan_id', 'tanggal', 'jam_masuk', 'jam_keluar', 'jumlah_jam_kerja'
    ];

    protected $dates = ['tanggal'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    // public function setJamKeluarAttribute($value)
    // {
    //     $this->attributes['jam_keluar'] = $value;

    //     if ($this->jam_masuk && $value) {
    //         $jamMasuk = \Carbon\Carbon::createFromFormat('H:i:s', $this->jam_masuk);
    //         $jamKeluar = \Carbon\Carbon::createFromFormat('H:i:s', $value);
    //         $this->attributes['jumlah_jam_kerja'] = $jamKeluar->diffInHours($jamMasuk);
    //     }
    // }

    public function toSearchableArray()
    {
        $array = $this->toArray();
        return [
            'id' => $array['id'],
            'tanggal' => $array['tanggal'],
            'karyawan_id' => $array['karyawan_id'],
            'keterangan' => $array['keterangan'],
            // Kolom lainnya yang ingin dicari
        ];
    }
}
