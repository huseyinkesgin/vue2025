<?php

namespace App\Http\Requests\Lokasyon;

use Illuminate\Foundation\Http\FormRequest;

class IlStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'Kod' => 'required|string|max:10|unique:il,Kod',
            'IlAdi' => 'required|string|max:255',
            'Durum' => 'required|string|in:Aktif,Pasif',
            'Aciklama' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'Kod.required' => 'Kod alanı zorunludur.',
            'Kod.unique' => 'Bu Kod zaten kullanılıyor.',
            'IlAdi.required' => 'Il Adı alanı zorunludur.',
            'Durum.required' => 'Durum alanı zorunludur.',
        ];
    }
}
