<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KetenagakerjaanJkk extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function settings()
    {
        return $this->belongsTo(settings::class, 'bpjs_ketenagakerjaan_jkk');
    }
}
