<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    protected $table = 'tools';
    protected $primaryKey = 'id';
   protected $fillable = [
        'nama_alat',
        'kode_alat',
        'merk',
        'jumlah_total',
        'jumlah_tersedia',
        'category_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->kode_alat)) {
                $lastTool = Tool::latest('id')->first();
                $nextNumber = $lastTool ? ((int) substr($lastTool->kode_alat, 3)) + 1 : 1;
                $model->kode_alat = 'BRX' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function borrowingDetails()
    {
        return $this->hasMany(BorrowingDetail::class,'tool_id');
    }
}
