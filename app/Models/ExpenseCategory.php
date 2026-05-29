<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    // Override Eloquent database access for static backward compatibility
    public static function all($columns = ['*'])
    {
        $categories = [
            ['id' => 1, 'name' => 'Bensin/BBM', 'slug' => 'bensin-bbm'],
            ['id' => 2, 'name' => 'Pajak/STNK', 'slug' => 'pajak-stnk'],
            ['id' => 3, 'name' => 'Gaji Karyawan', 'slug' => 'gaji-karyawan'],
            ['id' => 4, 'name' => 'Servis Gedung', 'slug' => 'servis-gedung'],
            ['id' => 5, 'name' => 'Biaya Operasional', 'slug' => 'biaya-operasional'],
            ['id' => 6, 'name' => 'Lainnya', 'slug' => 'lainnya'],
        ];

        return collect($categories)->map(fn ($cat) => (object) $cat);
    }

    public static function find(mixed $id, array|string $columns = ['*'])
    {
        // Parameter $columns diabaikan karena data hardcoded (tidak query ke DB)
        return static::all()->firstWhere('id', $id);
    }
}
