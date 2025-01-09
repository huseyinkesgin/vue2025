<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Il extends Model
{
    protected $table = 'il';

    const CREATED_AT = 'EklenmeTarihi';
    const UPDATED_AT = 'DuzenlenmeTarihi';

    protected $fillable = [
        'Kod',
        'IlAdi',
        'Durum',
        'Aciklama'
    ];

    protected $appends = ['eklenme_tarihi_format', 'duzenlenme_tarihi_format'];

    public function getEklenmeTarihiFormatAttribute()
    {
        return Carbon::parse($this->EklenmeTarihi)->format('d.m.Y');
    }

    public function getDuzenlenmeTarihiFormatAttribute()
    {
        return $this->DuzenlenmeTarihi ? Carbon::parse($this->DuzenlenmeTarihi)->format('d.m.Y') : '-';
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('Kod', 'like', "%{$search}%")
                ->orWhere('IlAdi', 'like', "%{$search}%")
                ->orWhere('Durum', 'like', "%{$search}%")
                ->orWhere('Aciklama', 'like', "%{$search}%");
        });
    }
}
