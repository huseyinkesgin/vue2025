<?php

namespace App\Http\Controllers\Lokasyon;

use App\Http\Controllers\Controller;
use App\Models\Il;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IlKontrolYonetimi extends Controller
{
    public function index(Request $request)
    {
        $query = Il::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('Kod', 'like', "%{$search}%")
                    ->orWhere('IlAdi', 'like', "%{$search}%");
            });
        }

        $iller = $query->paginate($request->get('per_page', 20));

        return Inertia::render('Lokasyon/IlSayfalari/IlIndex', [
            'iller' => $iller,
            'filters' => $request->all(['search', 'per_page'])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'Kod' => 'required|unique:il,Kod',
            'IlAdi' => 'required|string|max:255|unique:il,IlAdi',
            'Durum' => 'required',
            'Aciklama' => 'nullable|string'
        ], [
            'Kod.required' => 'Kod alanı zorunludur.',
            'Kod.unique' => 'Bu kod zaten kullanılmakta.',
            'IlAdi.required' => 'İl adı alanı zorunludur.',
            'IlAdi.unique' => 'Bu il adı zaten kullanılmakta.',
            'Durum.required' => 'Durum alanı zorunludur.'
        ]);

        Il::create($validated);

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'Kod' => 'required|unique:il,Kod,' . $id,
            'IlAdi' => 'required|string|max:255|unique:il,IlAdi,' . $id,
            'Durum' => 'required',
            'Aciklama' => 'nullable|string'
        ], [
            'Kod.required' => 'Kod alanı zorunludur.',
            'Kod.unique' => 'Bu kod zaten kullanılmakta.',
            'IlAdi.required' => 'İl adı alanı zorunludur.',
            'IlAdi.unique' => 'Bu il adı zaten kullanılmakta.',
            'Durum.required' => 'Durum alanı zorunludur.'
        ]);

        $il = Il::findOrFail($id);
        $il->update($validated);

        return redirect()->back();
    }

    public function destroy($id)
    {
        $il = Il::findOrFail($id);
        $il->delete();

        return redirect()->back();
    }

    public function latestKod()
    {
        $latestIl = Il::orderBy('Kod', 'desc')->first();
        return $latestIl ? $latestIl->Kod : null;
    }
}