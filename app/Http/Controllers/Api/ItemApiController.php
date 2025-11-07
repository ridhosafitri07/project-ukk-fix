<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemApiController extends Controller
{
    /**
     * Get all items
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $items = Item::orderBy('nama_item', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $items->map(function($item) {
                return [
                    'id_item' => $item->id_item,
                    'nama_item' => $item->nama_item,
                    'deskripsi' => $item->deskripsi ?? null,
                ];
            })
        ], 200);
    }

    /**
     * Get single item
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id_item' => $item->id_item,
                'nama_item' => $item->nama_item,
                'deskripsi' => $item->deskripsi ?? null,
            ]
        ], 200);
    }
}
