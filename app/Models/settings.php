<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class settings extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function KetenagakerjaanJkk()
    {
        return $this->hasOne(KetenagakerjaanJkk::class, 'bpjs_ketenagakerjaan_jkk');
    }
}
