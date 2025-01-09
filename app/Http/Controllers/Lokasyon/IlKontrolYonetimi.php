<?php

namespace App\Http\Controllers\Lokasyon;

use Inertia\Inertia;
use App\Models\Il;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Lokasyon\IlStoreRequest;
use App\Http\Requests\Lokasyon\IlUpdateRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class IlKontrolYonetimi extends Controller
{
    public function index(Request $request)
    {
        $query = Il::query();

        // Sayfalama
        $perPage = (int) $request->input('per_page', 20);
        $perPage = in_array($perPage, [10, 20, 50, 100]) ? $perPage : 20;

        // Arama işlemi
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sıralama
        $query->orderBy('id', 'desc');

        $iller = $query->paginate($perPage);

        return Inertia::render('Lokasyon/IlSayfalari/IlIndex', [
            'iller' => $iller,
            'filters' => [
                'search' => $request->input('search', ''),
                'per_page' => $perPage
            ]
        ]);
    }

    public function store(IlStoreRequest $request)
    {
        try {
            Log::info('Il store request data:', $request->all());

            $il = new Il();
            $il->Kod = $request->input('Kod');
            $il->IlAdi = $request->input('IlAdi');
            $il->Durum = $request->input('Durum');
            $il->Aciklama = $request->input('Aciklama');
            $il->save();

            Log::info('Il created successfully:', $il->toArray());
            return response()->json($il, 201);
        } catch (\Exception $e) {
            Log::error('Il store error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => 'Kayıt işlemi başarısız',
                'message' => $e->getMessage(),
                'details' => config('app.debug') ? $e->getTrace() : []
            ], 500);
        }
    }

    public function show($id)
    {
        return Il::find($id);
    }

    public function update(IlUpdateRequest $request, $id)
    {
        try {
            Log::info('Il update request data:', $request->all());

            $il = Il::findOrFail($id);
            $il->update($request->all());

            Log::info('Il updated successfully:', $il->toArray());
            return response()->json($il, 200);
        } catch (\Exception $e) {
            Log::error('Il update error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'error' => 'Güncelleme işlemi başarısız',
                'message' => $e->getMessage(),
                'details' => config('app.debug') ? $e->getTrace() : []
            ], 500);
        }
    }

    public function destroy($id)
    {
        Il::destroy($id);
        return response()->json(null, 204);
    }

    public function latestKod()
    {
        try {
            $latestIl = Il::orderBy('id', 'desc')->first();
            $kod = $latestIl ? $latestIl->Kod : 'IL-00000';
            return response()->json($kod);
        } catch (\Exception $e) {
            Log::error('Latest kod error: ' . $e->getMessage());
            return response()->json('IL-00000');
        }
    }
}