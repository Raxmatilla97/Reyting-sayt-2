<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Faculty;
use Illuminate\Support\Str; 

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $token = env('API_HEMIS_TOKEN');
        $apiUrl = env('API_HEMIS_URL').'/rest/v1/data/department-list?limit=200&active=1&_structure_type=11&localityType.name=Mahalliy&structureType.name=Fakultet';

        // API dan ma'lumotlarni olish
        $faculties = json_decode(file_get_contents($apiUrl, false, stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Authorization: Bearer ' . $token,
                    'Content-Type: application/json'
                ]
            ]
        ])), true);
        

        // Fakultetlarni ma'lumotlar bazasiga qo'shish
        foreach ($faculties['data']['items'] as $faculty) {
            Faculty::create([
                'name' => $faculty['name'] . ' fakulteti',
                'slug' => Str::slug($faculty['name'] . "-fakulteti-sahifasi"),
                'status' => true // default qiymat
            ]);
        }
    }
}
