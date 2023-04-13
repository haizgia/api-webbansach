<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return Categories::all();
    }

    public function show($id)
    {
        return Categories::where('category_id', $id)->first();
    }

    public function store(Request $request)
    {

        $book = Categories::create($request->all());

        $res = [
            'success' => true,
            'message' => 'Tạo thể loại sách thành công',
            'data' => $book,
        ];

        return response()->json($res, 201);
    }

    public function update(Request $request, $id)
    {

        try {
            $book = Categories::where('category_id', $id)->update($request->all());
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'message' => 'Sửa thể loại sách không thành công'
            ];
            return response()->json($res, 400);
        }

        $res = [
            'success' => true,
            'message' => 'Sửa thể loại sách thành công',
            'data' => Categories::where('category_id', $id)->first(),
        ];

        return response()->json($res, 200);
    }

    public function destroy($id)
    {
        $book = Categories::where('category_id', $id)->get();
        if (count($book) > 0) {
            Categories::where('category_id', $id)->delete();

            return response()->json(null, 204);
        }

        $res = [
            'success' => false,
            'message' => 'Mã thể loại sách không tồn tại',
        ];
        return response()->json($res, 400);
    }
}
