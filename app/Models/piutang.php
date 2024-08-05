<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class piutang extends Model
{
    use HasFactory;
    protected $table = "piutang";
    protected $primaryKey = "id_piutang";
    protected $fillable = ['piutang_tanggal', 'piutang_nominal', 'piutang_keterangan'];
}
