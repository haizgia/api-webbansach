<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function __construct(User $user){
        $this->user = $user;
    }
    //
    function register(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
        ]);

        if($validator->fails()){
            $res = [
              'success' => false,
              'message' => 'Lỗi kiểm tra dữ liệu',
              'data' => $validator->errors()
            ];
            return response()->json($res, 200);
        }

            $data = [
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
            ];

        try {
            $user = $this->user->create($data);
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'message' => 'Đăng ký không thành công'
            ];
            return response()->json($res, 400);
        }
        // $role = Role::create(['name' => 'user']);

        $user->syncRoles('user');
        // After the user is created, he's logged in.
        // $this->guard()->login($user);
        Auth::guard()->login($user);

        return $this->registered($request, $user)
                    ?: redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        $user->generateToken();

        $res = [
            'success' => true,
            'data' => $user->toArray(),
            'message' => 'Đăng ký thành công'
        ];

        return response()->json($res, 201);
    }

    function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => "Vui lòng nhập email",
            'password.required' => "Vui lòng nhập mật khẩu",
        ]);

        if (Auth::attempt([
            'email' => $request['email'],
            'password' => $request['password'],
        ])) {
            // $user = $request->user();
            $user = Auth::guard()->user();
            $user->generateToken();

            $success['name'] = $user->name;
            $success['email'] = $user->email;
            $success['api_token'] = $user->api_token;

            $res = [
                'success' => true,
                'data' => $success,
                'message' => 'Đăng nhập thành công'
            ];

            return response()->json($res, 200);
        }

        $res = [
            'success' => false,
            'message' => 'Email hoặc mật khẩu không chính xác'
        ];
        return response()->json($res, 400);
    }

    public function logout(Request $request) {
        $user = Auth::guard('api')->user();

        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return response()->json(['data' => 'User logged out.'], 200);
    }
}
