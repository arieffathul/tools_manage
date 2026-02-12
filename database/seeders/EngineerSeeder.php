<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EngineerSeeder extends Seeder
{
    public function run()
    {
        $engineers = [
            [
                'name' => 'Arief',
                'shift' => 'day',
                'status' => 'active',
                'created_at' => '2026-02-06 08:21:01',
                'updated_at' => '2026-02-06 08:21:01',
            ],
            [
                'name' => 'Wayan',
                'shift' => 'day',
                'status' => 'active',
                'created_at' => '2026-02-09 02:58:00',
                'updated_at' => '2026-02-09 02:58:00',
            ],
            [
                'name' => 'Fadiel',
                'shift' => 'day',
                'status' => 'active',
                'created_at' => '2026-02-09 02:58:09',
                'updated_at' => '2026-02-09 02:58:09',
            ],
            [
                'name' => 'Asep',
                'shift' => 'day',
                'status' => 'active',
                'created_at' => '2026-02-09 02:58:21',
                'updated_at' => '2026-02-09 02:58:21',
            ],
            [
                'name' => 'Indra',
                'shift' => 'day',
                'status' => 'active',
                'created_at' => '2026-02-09 02:58:28',
                'updated_at' => '2026-02-09 02:58:28',
            ],
        ];

        foreach ($engineers as $engineer) {
            DB::table('engineers')->insert($engineer);
        }
    }
}
