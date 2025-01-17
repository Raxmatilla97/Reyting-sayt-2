<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function index()
    {
        return view('dashboard.settings');
    }

    public function update(Request $request)
{
    try {
        \Log::info('Request keldi:', $request->all()); // Request kelganini logga yozish
        
        $envFile = base_path('.env');
        $content = file_get_contents($envFile);
        
        $content = preg_replace(
            '/ALLOW_DATA_ENTRY=(.*)/',
            'ALLOW_DATA_ENTRY=' . ($request->allow_data_entry == 1 ? 'true' : 'false'),
            $content
        );
        
        file_put_contents($envFile, $content);
        
        // Cache'ni tozalash
        Artisan::call('config:clear');
        
        \Log::info('Sozlamalar saqlandi'); // Muvaffaqiyatli saqlangani logga yozish
        
        return response()->json([
            'success' => true,
            'message' => 'Sozlamalar muvaffaqiyatli saqlandi'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Xatolik:', ['error' => $e->getMessage()]); // Xatolikni logga yozish
        
        return response()->json([
            'success' => false,
            'message' => 'Xatolik yuz berdi: ' . $e->getMessage()
        ], 500);
    }
}
}