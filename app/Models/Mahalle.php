<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahalle extends Model
{
    // Tablo adını belirtiyoruz
    protected $table = 'Mahalle';

    // Tarih sütunlarının adlarını belirtiyoruz
    const CREATED_AT = 'EklenmeTarihi';
    const UPDATED_AT = 'DuzenlenmeTarihi';
}
