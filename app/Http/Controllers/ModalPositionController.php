<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModalPositionController extends Controller
{
    public function save(Request $request)
    {
        $data = [
            'top' => $request->input('top'),
            'left' => $request->input('left')
        ];

        $xml = new \SimpleXMLElement('<modalPosition/>');
        array_walk_recursive($data, array ($xml, 'addChild'));
        Storage::put('modal_position.xml', $xml->asXML());

        return response()->json(['message' => 'Position saved successfully']);
    }

    public function load()
    {
        if (!Storage::exists('modal_position.xml')) {
            return response()->json(['top' => '50%', 'left' => '50%']);
        }

        $xml = simplexml_load_string(Storage::get('modal_position.xml'));
        $position = json_decode(json_encode($xml), true);

        return response()->json($position);
    }
}
