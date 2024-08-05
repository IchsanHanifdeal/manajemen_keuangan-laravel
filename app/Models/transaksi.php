<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaksi extends Model
{
    use HasFactory;

    protected $table = "transaksi";
    protected $primaryKey = "id_transaksi";
    protected $fillable = ['transaksi_tanggal', 'transaksi_jenis', 'id_kategori', 'transaksi_nominal', 'transaksi_keterangan', 'id_bank'];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'id_bank');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }
}
