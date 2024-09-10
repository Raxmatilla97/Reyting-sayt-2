<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartPoints;
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

    // Boshqa metodlar...

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

        // Model nomini yaratish. Masalan, 'user' uchun 'App\Models\User' qaytaradi
        return "App\\Models\\{$className}";
    }

}
