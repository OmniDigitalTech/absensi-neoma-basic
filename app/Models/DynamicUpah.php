<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicUpah extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function Golongan()
    {
        return $this->belongsTo(Golongan::class);
    }
}
