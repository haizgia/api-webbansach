<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bills;
use App\Models\Customers;
use App\Models\Billdetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function index()
    {
        return Bills::join('customers', 'bills.customer_id', '=', 'customers.id')
        ->join('billdetails', 'bills.bill_id', '=', 'billdetails.bill_id')
        ->join('statuses', 'bills.status_id', '=', 'statuses.status_id')
        ->whereNot('bills.status_id', 4)
        ->get();
    }

    public function show($id)
    {
        return Bills::where('bills.bill_id', $id)
        ->join('customers', 'bills.customer_id', '=', 'customers.id')
        ->join('billdetails', 'bills.bill_id', '=', 'billdetails.bill_id')
        ->join('statuses', 'bills.status_id', '=', 'statuses.status_id')
        ->first();
    }

    public function store(Request $request)
    {
        $uid = $request->user()['id'];
        // create customer
        $data = [
            'user_id' => $uid,
            'fullname' => $request['fullname'],
            'address' => $request['address'],
            'phone' => $request['phone'],
        ];
        $cus = Customers::create($data);

        // create bill
        $data = [
            'customer_id' => $cus['id'],
            'note' => $request['note'],
            'total_quanty' => $request['total_quanty'],
            'total_price' => $request['total_price'],
        ];
        $bill = Bills::create($data);

        // create detail bill
        foreach ($request['books'] as $key => $book) {
            $data = $book;
            $data['bill_id'] = $bill['id'];
            Billdetails::create($data);
        }

        try {
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'message' => 'Thêm hoá đơn không thành công'
            ];
            return response()->json($res, 400);
        }
        $res = [
            'success' => true,
            'message' => 'Thêm hoá đơn thành công',
            'bill' => $bill,
            'detail bill' => Billdetails::where('bill_id', $bill['id'])->get(),
        ];

        return response()->json($res, 201);
    }

    public function update(Request $request, $id)
    {

        try {
            $book = Bills::where('bill_id', $id)->update($request->all());
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'message' => 'Sửa hoá đơn không thành công'
            ];
            return response()->json($res, 400);
        }

        $res = [
            'success' => true,
            'message' => 'Sửa hoá đơn thành công',
            'data' => Bills::where('bill_id', $id)->first(),
        ];

        return response()->json($res, 200);
    }

    public function destroy($id)
    {
        $book = Bills::where('bill_id', $id)->get();
        if (count($book) > 0) {
            Bills::where('bill_id', $id)->delete();

            $res = [
                'success' => true,
                'message' => 'Xoá hoá đơn thành công',
            ];
            return response()->json($res, 204);
        }

        $res = [
            'success' => false,
            'message' => 'Mã hoá đơn không tồn tại',
        ];
        return response()->json($res, 400);
    }

    public function getListByUser(Request $request)
    {
        $id = Auth::id();
        return Bills::join('customers', 'bills.customer_id', '=', 'customers.id')
        ->join('billdetails', 'bills.bill_id', '=', 'billdetails.bill_id')
        ->join('statuses', 'bills.status_id', '=', 'statuses.status_id')
        ->where('customers.user_id', $id)
        ->get();
    }

    public function updateStatus(Request $request, $id)
    {
        // return response()->json($request['status_id'], 200);

        if ($request['status_id'] != null) {
            try {
                $bill = Bills::where('bill_id', $id)->update(['status_id' => $request['status_id']]);
                $res = [
                    'success' => true,
                    'message' => 'Sửa trạng thái đơn hàng thành công',
                    'data' => Bills::where('bill_id', $id)->first(),
                ];

                return response()->json($res, 200);
            }catch (\Exception $e) {
                $res = [
                    'success' => false,
                    'message' => 'Sửa trạng thái đơn hàng không thành công'
                ];
                return response()->json($res, 400);
            }
        }
    }

    public function cancel(Request $request, $id)
    {
        $bill = Bills::join('customers', 'bills.customer_id', '=', 'customers.id')->where('bill_id', $id)->first();
        $uid = $request->user()['id'];

        if ($bill['status_id'] != 1 || $bill['user_id'] != $uid) {
            $res = [
                'success' => false,
                'message' => 'Không thể huỷ đơn hàng'
            ];
            return response()->json($res, 400);
        }

        try {
            $bill = Bills::where('bill_id', $id)->update(['status_id' => 4]);
            $res = [
                'success' => true,
                'message' => 'Huỷ đơn hàng thành công',
                'data' => Bills::where('bill_id', $id)->first(),
            ];

            return response()->json($res, 200);
        }catch (\Exception $e) {
            $res = [
                'success' => false,
                'message' => 'Huỷ đơn hàng không thành công'
            ];
            return response()->json($res, 400);
        }
    }
}
