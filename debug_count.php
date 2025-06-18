<?php
require_once 'vendor/autoload.php';

// Laravel bootstrapping
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PointUserDeportament;

try {
    echo "=== Status 1 bo'lgan ma'lumotlar soni ===\n";
    
    $totalCount = PointUserDeportament::where('status', 1)->count();
    echo "Jami status=1: $totalCount\n";
    
    $withEmployee = PointUserDeportament::where('status', 1)
        ->whereHas('employee', function($query) {
            $query->whereNotNull('id');
        })->count();
    echo "Employee mavjud: $withEmployee\n";
    
    $withDepartment = PointUserDeportament::where('status', 1)
        ->whereHas('department', function($query) {
            $query->whereNotNull('id');
        })->count();
    echo "Department mavjud: $withDepartment\n";
    
    $withBoth = PointUserDeportament::where('status', 1)
        ->whereHas('employee', function($query) {
            $query->whereNotNull('id');
        })
        ->whereHas('department', function($query) {
            $query->whereNotNull('id');
        })->count();
    echo "Employee va Department mavjud: $withBoth\n";
    
    echo "\n=== Har bir jadval uchun ma'lumotlar soni ===\n";
    
    $tables = [
        'table_2', 'table_3', 'table_4', 'table_7', 'table_8_1', 'table_8_2',
        'table_9_1', 'table_9_2', 'table_10_1', 'table_10_2', 'table_10_3',
        'table_11_1', 'table_11_2', 'table_11_3', 'table_12', 'table_13',
        'table_14_1', 'table_14_2', 'table_14_3', 'table_15_1', 'table_15_2',
        'table_16', 'table_17_1', 'table_17_2', 'table_18_1', 'table_18_2',
        'table_18_3', 'table_19', 'table_20_1', 'table_20_2', 'table_20_3',
        'table_21_1', 'table_21_2', 'table_22', 'table_23', 'table_24'
    ];
    
    $grandTotal = 0;
    
    foreach ($tables as $table) {
        $count = PointUserDeportament::where('status', 1)
            ->whereHas('employee', function($query) {
                $query->whereNotNull('id');
            })
            ->whereHas('department', function($query) {
                $query->whereNotNull('id');
            })
            ->whereHas($table, function($query) {
                $query->whereNotNull('id');
            })
            ->count();
        
        echo "$table: $count ta yozuv\n";
        $grandTotal += $count;
    }
    
    echo "\nJami barcha jadvallar: $grandTotal ta yozuv\n";
    
} catch (Exception $e) {
    echo "Xatolik: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
} 