<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Publishers;
use Illuminate\Http\Request;

class PublisherController extends Controller
{
    public function index()
    {
        return Publishers::all();
    }

    public function show($id)
    {
        return Publishers::where('publisher_id', $id)->first();
    }

    public function store(Request $request)
    {

        $book = Publishers::create($request->all());

        $res = [
            'success' => true,
            'message' => 'Tạo nxb thành công',
            'data' => $book,
        ];

        return response()->json($res, 201);
    }

    public function update(Request $request, $id)
    {

        try {
            $book = Publishers::where('publisher_id', $id)->update($request->all());
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'message' => 'Sửa nxb không thành công'
            ];
            return response()->json($res, 400);
        }

        $res = [
            'success' => true,
            'message' => 'Sửa nxb thành công',
            'data' => Publishers::where('publisher_id', $id)->first(),
        ];

        return response()->json($res, 200);
    }

    public function destroy($id)
    {
        $book = Publishers::where('publisher_id', $id)->get();
        if (count($book) > 0) {
            Publishers::where('publisher_id', $id)->delete();

            return response()->json(null, 204);
        }

        $res = [
            'success' => false,
            'message' => 'Mã nxb không tồn tại',
        ];
        return response()->json($res, 400);
    }
}
