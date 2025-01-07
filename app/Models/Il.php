<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasKod;

class Il extends Model
{
    use HasKod;

    // Tablo adını belirtiyoruz
    protected $table = 'Il';

    // Tarih sütunlarının adlarını belirtiyoruz
    const CREATED_AT = 'EklenmeTarihi';
    const UPDATED_AT = 'DuzenlenmeTarihi';
}
