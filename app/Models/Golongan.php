<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Golongan extends Model
{
    use HasFactory;
    protected $guarded = ["id"];

    public function User()
    {
        return $this->hasMany(User::class);
    }

    public function Tunjangan()
    {
        return $this->hasOne(Tunjangan::class);
    }

    public function Upah()
    {
        return $this->hasOne(Upah::class);
    }

    public function Deduksi()
    {
        return $this->hasOne(Deduksi::class);
    }
}
