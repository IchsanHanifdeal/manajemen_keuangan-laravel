<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hutang extends Model
{
    use HasFactory;
    protected $table = "hutang";
    protected $primaryKey = "id_hutang";
    protected $fillable = ['hutang_tanggal', 'hutang_nominal', 'hutang_keterangan'];
}
