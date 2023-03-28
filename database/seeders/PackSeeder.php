<?php

namespace Database\Seeders;

use App\Models\Pack;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                "id" => 1,
            "name"=> "Expansion Pack"
            ],
            [
                "id" => 2,
            "name"=> "Game Pack"
            ]
        ];

        foreach($data as $d) {
            Pack::updateOrCreate($d);
        }
    }
}
