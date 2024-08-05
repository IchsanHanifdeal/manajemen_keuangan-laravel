<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bank extends Model
{
    use HasFactory;

    protected $table = "bank";
    protected $primaryKey = "id_bank";
    protected $fillable = ['nama_bank', 'nomor_rekening', 'pemilik', 'saldo_bank'];
}
