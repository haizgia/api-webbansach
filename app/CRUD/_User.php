<?php

namespace App\CRUD;

use App\Models\User;
// nếu dùng slug thì dùng thư viện
// use Illuminate\Support\Str;
// 'slug' => Str::slug($data['slug'])

class _User {
    public function create($data) {
        try {
            $result = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            if ($result->id > 0) {
                return $result;
            }
            return false;
        } catch (\Exception $e) {
            return $e;
        }
        return 'ERROR';
    }

    public function update($data, $id) {
        try {
            $result = User::where('id','=',$id)
            ->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            if ($result) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return $e;
        }
        return 'ERROR';
    }

    public function delete($id) {
        try {
            $result = User::where('id', '=', $id)->delete();
            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function deleteMulti($ids) {
        try {
            $result = User::whereIn('id', $ids)->delete();
            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
    }
}
