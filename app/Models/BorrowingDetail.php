<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingDetail extends Model
{
    protected $table = 'borrowing_details';
    protected $primaryKey = 'id';
    protected $fillable = [
        'borrowing_id',
        'tool_id',
        'jumlah_pinjam',
        'kondisi_awal',
        'kondisi_akhir',
        'keterangan_awal',
        'keterangan_akhir',
    ];

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class,'borrowing_id');
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class,'tool_id');
    }
}
