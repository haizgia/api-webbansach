<?php

namespace App\CRUD;

use App\Models\Products;
use DB;
// nếu dùng slug thì dùng thư viện
// use Illuminate\Support\Str;
// 'slug' => Str::slug($data['slug'])

class _Product {
    public function create($data) {
        try {
            $result = Products::create([
                'category_id' => $data['category_id'],
                'publisher_id' => $data['publisher_id'],
                'name' => $data['name'],
                'image' => $data['image'],
                'price' => $data['price'],
                'quanty' => $data['quanty'],
                'author' => $data['author'],
                'content' => $data['content'],
            ]);
            if ($result->id > 0) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return $e;
        }
        return 'ERROR';
    }

    public function update($data, $id) {
        try {
            $result = Products::where('book_id','=',$id)
            ->update([
                'category_id' => $data['category_id'],
                'publisher_id' => $data['publisher_id'],
                'name' => $data['name'],
                'image' => $data['image'],
                'price' => $data['price'],
                'quanty' => $data['quanty'],
                'author' => $data['author'],
                'content' => $data['content'],
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
            $result = Products::where('book_id', '=', $id)->delete();
            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function deleteMulti($ids) {
        try {
            $result = Products::whereIn('book_id', $ids)->delete();
            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getById($id){
        try {
            $data = Products::select('*')->join('chitietsaches', 'saches.book_id', '=', 'chitietsaches.book_id')
            ->join('nganhs', 'nganhs.manganh', '=', 'saches.manganh')
            ->join('vitris', 'saches.mavt', '=', 'vitris.mavt')
            ->join('loais', 'loais.maloai', '=', 'saches.maloai')
            ->join('trangthais', 'trangthais.id', '=', 'saches.tinhtrang')
            ->where('saches.book_id', '=', $id)
            ->first();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getOneNew(){
        $data = Products::select('*')
        ->orderBy('book_id', 'DESC')
        ->limit(1)
        ->get();
        return $data;
    }

    public function getEightNew(){
        $data = Products::select('*')
        ->join('chitietsaches', 'saches.book_id', '=', 'chitietsaches.book_id')
        ->join('nganhs', 'nganhs.manganh', '=', 'saches.manganh')
        ->join('vitris', 'saches.mavt', '=', 'vitris.mavt')
        ->join('loais', 'saches.maloai', '=', 'loais.maloai')
        ->join('trangthais', 'saches.tinhtrang', '=', 'trangthais.id')
        ->orderBy('saches.book_id', 'DESC')
        ->limit(8)
        ->get();
        return $data;
    }

    public function getGoodBook(){
        $data = Products::select('*')
        ->select(DB::raw('count(*) as count'), 'saches.book_id')
        ->join('muontras', 'saches.book_id', '=', 'muontras.book_id')
        ->groupBy('saches.book_id')
        ->orderBy('count', 'DESC')
        ->limit(8)
        ->get();
        return $data;
    }

    public function getNew()
    {
        try {
            $data = Products::select('*')
            ->join('chitietsaches', 'saches.book_id', '=', 'chitietsaches.book_id')
            ->join('nganhs', 'nganhs.manganh', '=', 'saches.manganh')
            ->join('vitris', 'saches.mavt', '=', 'vitris.mavt')
            ->join('loais', 'saches.maloai', '=', 'loais.maloai')
            ->join('trangthais', 'saches.tinhtrang', '=', 'trangthais.id')
            ->orderBy('saches.book_id', 'DESC')
            ->paginate(4);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function search($col_name, $key, $limit, $order, $by)
    {
        try {
            $data = Products::select('*')
            ->join('chitietsaches', 'saches.book_id', '=', 'chitietsaches.book_id')
            ->join('nganhs', 'nganhs.manganh', '=', 'saches.manganh')
            ->join('vitris', 'saches.mavt', '=', 'vitris.mavt')
            ->join('loais', 'saches.maloai', '=', 'loais.maloai')
            ->join('trangthais', 'saches.tinhtrang', '=', 'trangthais.id')
            ->where('chitietsaches.'.$col_name, 'like', "%$key%")
            ->orderBy($order, $by)
            // ->orderBy($orderby)
            ->paginate($limit);

            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function searchWithType($col_name, $key, $type, $idtype, $limit, $order, $by)
    {
        try {
            $data = Products::select('*')
            ->join('chitietsaches', 'saches.book_id', '=', 'chitietsaches.book_id')
            ->join('nganhs', 'nganhs.manganh', '=', 'saches.manganh')
            ->join('vitris', 'saches.mavt', '=', 'vitris.mavt')
            ->join('loais', 'saches.maloai', '=', 'loais.maloai')
            ->join('trangthais', 'saches.tinhtrang', '=', 'trangthais.id')
            ->where('chitietsaches.'.$col_name, 'like', "%$key%")
            ->where($type, $idtype)
            ->orderBy($order, $by)
            ->paginate($limit);

            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getByMajors($nganh)
    {
        try {
            $data = Products::select('*')->join('chitietsaches', 'saches.book_id', '=', 'chitietsaches.book_id')
            ->join('nganhs', 'nganhs.manganh', '=', 'saches.manganh')
            ->join('vitris', 'saches.mavt', '=', 'vitris.mavt')
            ->where('nganhs.manganh', $nganh)
            ->orderBy('saches.book_id', 'DESC')
            ->paginate(4);

            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getByCategory($loai)
    {
        try {
            $data = Products::select('*')->join('chitietsaches', 'saches.book_id', '=', 'chitietsaches.book_id')
            ->join('nganhs', 'nganhs.manganh', '=', 'saches.manganh')
            ->join('loais', 'loais.maloai', '=', 'saches.maloai')
            ->join('vitris', 'saches.mavt', '=', 'vitris.mavt')
            ->where('loais.maloai', $loai)
            ->orderBy('saches.book_id', 'DESC')
            ->paginate(4);

            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getSearchDelete($col_name, $key)
    {
        try {
            $data = Products::onlyTrashed()->select('*')
            ->join('chitietsaches', 'saches.book_id', '=', 'chitietsaches.book_id')
            ->join('sach_nganhs', 'sach_nganhs.book_id', '=', 'saches.book_id')
            ->join('vitris', 'saches.mavt', '=', 'vitris.mavt')
            ->where('chitietsaches.'.$col_name, 'like', "%$key%")
            ->orderBy('saches.book_id', 'DESC')
            ->paginate(4);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function getDelete()
    {
        try {
            $data = Products::onlyTrashed()->select('*')
            ->join('chitietsaches', 'saches.book_id', '=', 'chitietsaches.book_id')
            // ->join('sach_nganhs', 'sach_nganhs.book_id', '=', 'saches.book_id')
            ->join('vitris', 'saches.mavt', '=', 'vitris.mavt')
            ->orderBy('saches.book_id', 'DESC')
            ->paginate(4);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function restore($id)
    {
        try {
            $result = Products::withTrashed()
            ->where('book_id', '=', $id)->restore();
            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function restore_multi($ids)
    {
        try {
            $result = Products::withTrashed()
            ->whereIn('book_id',$ids)->restore();
            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy($id)
    {
        try {
            $result = Products::withTrashed()
            ->where('book_id', '=', $id)->forceDelete();
            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function destroy_multi($ids)
    {
        try {
            $result = Products::withTrashed()
            ->whereIn('book_id', $ids)->forceDelete();
            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public function check($id)
    {
        try {
            $result = Products::where('book_id', $id)->first();
            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
    }

}
