<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $table = 'borrowings';
    protected $primaryKey = 'id';
   protected $fillable = [
        'user_id',
        'nama_peminjam',
        'tanggal_pinjam',
        'keperluan',
        'keterangan',
        'kondisi',
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class,'borrowing_id');
    }
}
