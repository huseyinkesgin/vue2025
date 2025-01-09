<?php

namespace App\Http\Requests\Lokasyon;

use Illuminate\Foundation\Http\FormRequest;

class IlUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'Kod' => 'required|string|max:10',
            'IlAdi' => 'required|string|max:255',
            'Durum' => 'required|string|in:Aktif,Pasif',
            'Aciklama' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'Kod.required' => 'Kod alanı zorunludur.',
            'IlAdi.required' => 'Il Adı alanı zorunludur.',
            'Durum.required' => 'Durum alanı zorunludur.',
        ];
    }
}
