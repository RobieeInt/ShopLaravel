<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    public function all(Request $request) {
        $id = $request->input('id');
        $name = $request->input('name');
       // Limit pagination
        $limit = $request->input('limit');
        $show_product = $request->input('show_product');

        if($id) {
            $category = ProductCategory::with(['products'])->find($id);

            if($category) {
                return ResponseFormatter::success(
                    $category,
                    'Category berhasil ditemukan');
            } else {
                return ResponseFormatter::error(
                    null,
                    'Category tidak ditemukan',
                    404);
            }
        }

        $category = ProductCategory::query();

        if($name) {
            $category->where('name', 'like', '%'.$name.'%');
        }

        if($show_product) {
            $category->with(['products' => function($query) {
                $query->limit(3);
            }]);
        }

        return ResponseFormatter::success(
            $category->paginate($limit),
            'Category berhasil ditemukan');
    }
}
