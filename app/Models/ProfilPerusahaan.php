<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfilPerusahaan extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'profileperusahaans';
    protected $fillable = [
        'nama_perusahaan', 'badan_hukum', 'alamat', 'no_telp','latitude','longitude','radius','status'
    ];
}
