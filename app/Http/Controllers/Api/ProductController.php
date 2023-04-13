<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Resources\Product as ProductResource;

class ProductController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('role:user', ['only' => ['index']]);
    // }

    public function index()
    {
        $products = Products::join('categories', 'products.category_id', '=', 'categories.category_id')
        ->join('publishers', 'products.publisher_id', '=', 'publishers.publisher_id')
        ->paginate(2);
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        return Products::where('book_id', $id)
        ->join('categories', 'products.category_id', '=', 'categories.category_id')
        ->join('publishers', 'products.publisher_id', '=', 'publishers.publisher_id')
        ->first();
    }

    public function store(Request $request)
    {
        $book = Products::create($request->all());
        try {
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'message' => 'Thêm sách không thành công'
            ];
            return response()->json($res, 400);
        }
        $res = [
            'success' => true,
            'message' => 'Thêm sách thành công',
            'data' => $book,
        ];

        return response()->json($res, 201);
    }

    public function update(Request $request, $id)
    {
        $book = Products::where('book_id', $id)->get();
        if (count($book) > 0) {
            try {
                $book = Products::where('book_id', $id)->update($request->all());
            } catch (\Exception $e) {
                $res = [
                    'success' => false,
                    'message' => 'Sửa sách không thành công'
                ];
                return response()->json($res, 400);
            }

            $res = [
                'success' => true,
                'message' => 'Sửa sách thành công',
                'data' => Products::where('book_id', $id)->first(),
            ];

            return response()->json($res, 200);
        }

        $res = [
            'success' => false,
            'message' => 'Mã sách không tồn tại',
        ];
        return response()->json($res, 400);
    }

    public function destroy($id)
    {
        $book = Products::where('book_id', $id)->get();
        if (count($book) > 0) {
            Products::where('book_id', $id)->delete();

            $res = [
                'success' => true,
                'message' => 'Xoá sách thành công',
            ];
            return response()->json($res, 204);
        }

        $res = [
            'success' => false,
            'message' => 'Mã sách không tồn tại',
        ];
        return response()->json($res, 400);
    }

    public function getNew()
    {
        $products = Products::join('categories', 'products.category_id', '=', 'categories.category_id')
        ->join('publishers', 'products.publisher_id', '=', 'publishers.publisher_id')
        ->orderBy('book_id', 'desc')
        ->paginate(2);
        return ProductResource::collection($products);
    }

    public function getCheapest()
    {
        $products = Products::join('categories', 'products.category_id', '=', 'categories.category_id')
        ->join('publishers', 'products.publisher_id', '=', 'publishers.publisher_id')
        ->orderBy('price')
        ->paginate(2);
        return ProductResource::collection($products);
    }

    public function getByCategory($id)
    {
        $products = Products::join('categories', 'products.category_id', '=', 'categories.category_id')
        ->join('publishers', 'products.publisher_id', '=', 'publishers.publisher_id')
        ->where('categories.category_id', $id)
        ->paginate(2);
        return ProductResource::collection($products);
    }

    public function search(Request $request)
    {
        if ($request['q'] !== null) {
            $key = $request['q'];
            $products = Products::join('categories', 'products.category_id', '=', 'categories.category_id')
            ->join('publishers', 'products.publisher_id', '=', 'publishers.publisher_id')
            ->where('products.name', 'like', "%$key%")
            ->paginate(2);
            $products->withPath(url()->full());
            return ProductResource::collection($products);
        }
        return $this->getNew();
    }
}
