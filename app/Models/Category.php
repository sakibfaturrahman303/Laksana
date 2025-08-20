<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nama_kategori',
    ];

    public function tool()
    {
        return $this->hasMany(Tool::class,'category_id');
    }
}
