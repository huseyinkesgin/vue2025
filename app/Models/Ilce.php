<?php

namespace App\Models;

use App\Traits\HasKod;
use Illuminate\Database\Eloquent\Model;

class Ilce extends Model
{
    use HasKod;

    
    protected $table = 'Ilce';

    // Tarih sütunlarının adlarını belirtiyoruz
    const CREATED_AT = 'EklenmeTarihi';
    const UPDATED_AT = 'DuzenlenmeTarihi';
}
