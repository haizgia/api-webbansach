<?php
namespace App\CRUD;

use App\Models\Publishers;

class _Publisher
{
    public function create($name) {
        try {
            $result = Publishers::create([
                'publisher_name' => $name,
            ]);
            if ($result['publisher_id'] > 0) {
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
            $result = Publishers::where('publisher_id', $id)
            ->update([
                'publisher_name' => $name,
            ]);

            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
        return 'ERROR';
    }

    public function delete($id) {
        try {
            $result = Publishers::where('publisher_id', $id)->delete();
            return $result ? true : false;
        } catch (\Exception $e) {
            return $e;
        }
    }
}


?>
