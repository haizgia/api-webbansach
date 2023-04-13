<?php
namespace App\CRUD;

use App\Models\Categories;

class _Category
{
    public function create($name) {
        try {
            $result = Categories::create([
                'category_name' => $name,
            ]);
            if ($result['category_id'] > 0) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return $e;
        }
        return 'ERROR';
    }

    public function update($id, $name) {
        try {
            $result = Categories::where('category_id', $id)
            ->update([
                'category_name' => $name,
            ]);

            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
        return 'ERROR';
    }

    public function delete($id) {
        try {
            $result = Categories::where('category_id', $id)->delete();
            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
    }
}


?>
