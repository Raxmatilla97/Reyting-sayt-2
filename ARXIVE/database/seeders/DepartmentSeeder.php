<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Str; 


class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $token = env('API_HEMIS_TOKEN');
        $apiUrl = env('API_HEMIS_URL').'/rest/v1/data/department-list?limit=200&active=1&_structure_type=12&structureType.name=Kafedra';

        // API dan ma'lumotlarni olish
        $departments = json_decode(file_get_contents($apiUrl, false, stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Authorization: Bearer ' . $token,
                    'Content-Type: application/json'
                ]
            ]
        ])), true);
        

        // Fakultetlarni ma'lumotlar bazasiga qo'shish
        foreach ($departments['data']['items'] as $department) {
            Department::create([
                'id' => $department['id'],
                'name' => $department['name'] . ' kafedrasi',
                'slug' => Str::slug($department['name'] . "-kafedra-sahifasi"),
                'status' => true, // default qiymat
                'faculty_id' => $department['parent']
            ]);
        }
    }
}
