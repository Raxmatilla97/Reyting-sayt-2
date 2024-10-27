<?php

namespace App\Http\Controllers;

use Log;
use App\Models\DepartPoints;
use Illuminate\Http\Request;
use App\Models\PointUserDeportament;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PointCalculationController extends Controller
{
    /**
     * Berilgan ma'lumot uchun tegishli ma'lumotlarni va foydalanuvchi ball ma'lumotlarini olish.
     *
     * @param PointUserDeportament $information Foydalanuvchi deportament ma'lumotlari
     * @return array [$relatedData, $userPointInfo, $foundRelation]
     */
    public function getRelatedData($information)
    {
        $relatedData = [];
        $userPointInfo = [
            'table_name' => '',
            'max_point' => 0,
            'total_points' => 0
        ];
        $relationships = $information->getRelationships();
        $maxPointsConfig = config('max_points_dep_emp');

        $foundRelation = false;
        if (is_array($relationships)) {
            foreach ($relationships as $relationship) {
                $foreignKey = $relationship . '_id';
                if (isset($information->{$foreignKey}) && !is_null($information->{$foreignKey})) {
                    try {
                        $relatedModelClass = $this->getModelClassForRelation($relationship);
                        if (class_exists($relatedModelClass)) {
                            $relatedData[$relationship] = $relatedModelClass::findOrFail($information->{$foreignKey});

                            $tableName = $relatedData[$relationship]->getTable();
                            $userPointInfo['table_name'] = $tableName;

                            foreach (['department', 'employee'] as $category) {
                                if (isset($maxPointsConfig[$category][$tableName])) {
                                    $userPointInfo['max_point'] = $maxPointsConfig[$category][$tableName]['max'];
                                    $foundRelation = true;
                                    break 2;
                                }
                            }
                        } else {
                            \Log::warning("Related model class not found: $relatedModelClass");
                        }
                    } catch (ModelNotFoundException $e) {
                        \Log::warning("Related model not found for $relationship with id {$information->{$foreignKey}}");
                    } catch (\Exception $e) {
                        \Log::error("Error processing relationship $relationship: " . $e->getMessage());
                    }
                } else {
                    $relatedData[$relationship] = null;
                }
            }
        }

        return [$relatedData, $userPointInfo, $foundRelation];
    }

    /**
     * Foydalanuvchining umumiy ballarini hisoblash.
     *
     * @param int $userId Foydalanuvchi ID si
     * @return array Umumiy ballar, departament ballari va departamentsiz ballar
     */
    public function calculateTotalPoints($userId)
    {
        // Foydalanuvchining asosiy ballarini hisoblash
        $totalPoints = PointUserDeportament::where('user_id', $userId)
            ->where('status', 1)
            ->sum('point');

        // Departament ballarini hisoblash
        $totalDepartmentPoints = DepartPoints::whereIn('point_user_deport_id', function ($query) use ($userId) {
            $query->select('id')
                ->from('point_user_deportaments')
                ->where('user_id', $userId)
                ->where('status', 1);
        })->sum('point');

        $totalPointsWithDepartment = $totalPoints + $totalDepartmentPoints;
        $totalPointsWithoutDepartment = $totalPoints;

        return [
            'totalPoints' => $totalPointsWithDepartment,
            'totalPointsWithoutDepartment' => $totalPointsWithoutDepartment,
            'totalDepartmentPoints' => $totalDepartmentPoints
        ];
    }

    /**
     * Foydalanuvchining ma'lum bir jadval uchun ballarini hisoblash.
     *
     * @param int $userId Foydalanuvchi ID si
     * @param string $tableName Jadval nomi
     * @return array Foydalanuvchi ball ma'lumotlari
     */
    public function calculateUserPointInfoSingle($userId, $tableName)
{
    // O'qituvchi ballarini hisoblash (departament ballari kirmagan holda)
    $teacherPoints = PointUserDeportament::where('user_id', $userId)
        ->where('status', 1)
        ->where(function ($query) use ($tableName) {
            $columns = Schema::getColumnListing('point_user_deportaments');
            foreach ($columns as $column) {
                if (strpos($column, $tableName) !== false) {
                    $query->orWhereNotNull($column);
                }
            }
        })
        ->sum('point');

    // Departament ballarini hisoblash
    $departmentPoints = DepartPoints::whereIn('point_user_deport_id', function ($query) use ($userId, $tableName) {
        $query->select('id')
            ->from('point_user_deportaments')
            ->where('user_id', $userId)
            ->where('status', 1)
            ->where(function ($q) use ($tableName) {
                $columns = Schema::getColumnListing('point_user_deportaments');
                foreach ($columns as $column) {
                    if (strpos($column, $tableName) !== false) {
                        $q->orWhereNotNull($column);
                    }
                }
            });
    })->sum('point');

    // Debug uchun
    \Log::info("User ID: $userId, Table: $tableName");
    \Log::info("Teacher Points (before correction): $teacherPoints");
    \Log::info("Department Points: $departmentPoints");

    // Hisoblash natijalarini qaytarish
    $result = [
        'total_points_for_teacher' => $teacherPoints,
        'total_department_points' => $departmentPoints
    ];

    \Log::info("Final Result: " . json_encode($result));

    return $result;
}




    public function calculateUserPointInfo($userId, $tableName)
    {
        // Asosiy ballarni hisoblash
        $userPointInfo = [
            'total_points' => PointUserDeportament::where('user_id', $userId)
                ->where('status', 1)
                ->where(function ($query) use ($tableName) {
                    $query->where(function ($q) use ($tableName) {
                        $columns = Schema::getColumnListing('point_user_deportaments');
                        foreach ($columns as $column) {
                            if (strpos($column, $tableName) !== false) {
                                $q->orWhereNotNull($column);
                            }
                        }
                    });
                })
                ->sum('point')
        ];

        // Departament ballarini qo'shish
        $userPointInfo['total_points'] += DepartPoints::whereIn('point_user_deport_id', function ($query) use ($userId, $tableName) {
            $query->select('id')
                ->from('point_user_deportaments')
                ->where('user_id', $userId)
                ->where('status', 1)
                ->where(function ($q) use ($tableName) {
                    $columns = Schema::getColumnListing('point_user_deportaments');
                    foreach ($columns as $column) {
                        if (strpos($column, $tableName) !== false) {
                            $q->orWhereNotNull($column);
                        }
                    }
                });
        })->sum('point');

        return $userPointInfo;
    }

    /**
     * Berilgan ID uchun departament balini olish.
     *
     * @param int $id PointUserDeportament ID si
     * @return float Departament bali
     */
    public function getDepartmentPoint($id)
    {
        return DepartPoints::where('point_user_deport_id', $id)->value('point') ?? 0;
    }

    /**
     * Munosabat uchun model sinfini olish.
     *
     * @param string $relationship Munosabat nomi
     * @return string Model sinfi nomi
     */
    private function getModelClassForRelation($relationship)
    {
        // Relationship nomini katta harf bilan boshlash
        $className = ucfirst($relationship);

        // Tegishli model uchun to'liq class nomini tuzish
        return "\\App\\Models\\Tables\\" . $className . "_";
    }
}
