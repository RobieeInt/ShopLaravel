<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use App\Models\ProductLike;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LikeProductController extends Controller
{

    public function all(Request $request) {
        // $productLikes = ProductLike::all('id', 'products_id', 'users_id');
        $productLikes = ProductLike::all();
        return ResponseFormatter::success(
            $productLikes,
            ['Product like berhasil ditampilkan']
        );
        // return response()->json($productLikes);
    }

    public function getlike(Request $request) {
        $request->validate([
            'products_id' => 'required|exists:products,id',
        ]);

        $productLikes = ProductLike::with(['product'])->where('products_id', $request->input('products_id'));


        if ($productLikes->count('products_id') > 0) {
            return ResponseFormatter::success(
                $productLikes->count('products_id'),
                ['Like berhasil ditampilkan']
            );
        }

        if($productLikes->count('products_id') == null || $productLikes->count('products_id') == 0) {
            return ResponseFormatter::error(
                0,
                ['Belum ada Like'],200
            );
        }
    }

    public function like(Request $request)
    {
        $productLikes = ProductLike::create([
            'users_id' => Auth::user()->id,
            'products_id' => $request->input('products_id'),
        ]);

        if($productLikes) {
            return ResponseFormatter::success(
                $productLikes,
                ['Like berhasil ditambahkan']
            );
        } else {
            return ResponseFormatter::error(
                0,
                ['Like gagal ditambahkan'],
                400
            );
        }
    }
}
