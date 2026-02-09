<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ToolSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $tools = [
            [
                'id' => 1,
                'code' => null,
                'name' => 'Fluke 1550C',
                'description' => 'Insulation Tester',
                'spec' => null,
                'quantity' => 1,
                'locator' => 'A.1.1.1',
                'current_locator' => 'A.1.1.1',
                'current_quantity' => 1,
                'last_audited_at' => null,
                'deleted_at' => null,
                'created_at' => '2026-02-06 08:56:41',
                'updated_at' => '2026-02-06 08:57:40',
            ],
            [
                'id' => 2,
                'code' => null,
                'name' => 'Fluke 1635C',
                'description' => 'Multifunction Installation Tester',
                'spec' => null,
                'quantity' => 1,
                'locator' => 'A.1.1.1',
                'current_locator' => 'A.1.1.1',
                'current_quantity' => 1,
                'last_audited_at' => null,
                'deleted_at' => null,
                'created_at' => '2026-02-09 03:00:57',
                'updated_at' => '2026-02-09 03:00:57',
            ],
            [
                'id' => 3,
                'code' => null,
                'name' => 'Fluke 116',
                'description' => 'Multimeter',
                'spec' => null,
                'quantity' => 3,
                'locator' => 'A.1.1.5',
                'current_locator' => 'A.1.1.5',
                'current_quantity' => 3,
                'last_audited_at' => null,
                'deleted_at' => null,
                'created_at' => '2026-02-09 03:03:19',
                'updated_at' => '2026-02-09 03:03:19',
            ],
        ];

        foreach ($tools as $tool) {
            DB::table('tools')->insert($tool);
        }
    }
}
