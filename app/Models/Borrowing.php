<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $table = 'borrowings';
    protected $primaryKey = 'id';
   protected $fillable = [
        'operator_pinjam',
        'operator_kembali',
        'nama_peminjam',
        'tanggal_pinjam',
        'keperluan',
        'catatan',  
        'tanggal_kembali_rencana',
        'tanggal_kembali_aktual',
        'status',
    ];

    public function operatorPinjam()
    {
        return $this->belongsTo(User::class,'operator_pinjam');
    }
    public function operatorKembali()
    {
        return $this->belongsTo(User::class,'operator_kembali');
    }

    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class,'borrowing_id');
    }
}
