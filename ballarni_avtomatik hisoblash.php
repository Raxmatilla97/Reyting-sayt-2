<?php

function hisoblash_1_1($P, $P1) {
    return 3 * $P1 / $P * 100;
}

function hisoblash_1_2($P, $P2) {
    return 2 * $P2 / $P * 100;
}

function hisoblash_1_3($P, $P3, $P4) {
    $I_1_3_1 = 2 * $P3 / $P;
    $I_1_3_2 = $P4 / $P;
    return ($I_1_3_1 + $I_1_3_2) * 100;
}

function hisoblash_1_4($P, $D) {
    return 6 * $D / $P;
}

function hisoblash_1_5($P, $S1) {
    return 9 * $S1 / $P;
}

function hisoblash_1_6($P, $S2, $S3) {
    $I_1_6_1 = 3 * $S2 / $P;
    $I_1_6_2 = 2 * $S3 / $P;
    return $I_1_6_1 + $I_1_6_2;
}

function hisoblash_1_7($P, $S4, $S5, $S6) {
    $I_1_7_1 = 2 * $S4 / $P;
    $I_1_7_2 = $S5 / $P;
    $I_1_7_3 = $S6 / $P;
    return $I_1_7_1 + $I_1_7_2 + $I_1_7_3;
}

function hisoblash_1_8($P_pu, $P5, $P6) {
    $I_1_8_1 = 2 * $P5 / $P_pu;
    $I_1_8_2 = 2 * $P6 / $P_pu;
    return ($I_1_8_1 + $I_1_8_2) / 4 * 100;
}

function hisoblash_1_9($P, $S7, $S8, $S9) {
    $I_1_9_1 = 2 * $S7 / $P;
    $I_1_9_2 = $S8 / $P;
    $I_1_9_3 = $S9 / $P;
    return $I_1_9_1 + $I_1_9_2 + $I_1_9_3;
}

function hisoblash_2_1($Tc, $T1, $T2, $T3, $T4) {
    $I_2_1_1 = 2 * $T1 / $Tc;
    $I_2_1_2 = 2 * $T2 / $Tc;
    $I_2_1_3 = $T3 / $Tc;
    $I_2_1_4 = 2 * $T4 / $Tc;
    return ($I_2_1_1 + $I_2_1_2 + $I_2_1_3 + $I_2_1_4) / 4 * 100;
}

function hisoblash_2_2($P, $S10, $S11) {
    $I_2_2_1 = 6 * $S10 / $P;
    $I_2_2_2 = 2 * $S11 / $P;
    return ($I_2_2_1 + $I_2_2_2) * 100;
}

function hisoblash_2_3($P, $T, $Px, $Tx) {
    $I_2_3_1 = 3 * $Px / $P * 100;
    $I_2_3_2 = 4 * $Tx / $T * 100;
    return $I_2_3_1 + $I_2_3_2;
}

function hisoblash_2_4($T, $P, $T5, $T6, $P7) {
    $I_2_4_1 = 3 * $T5 / $T;
    $I_2_4_1_1 = 2 * $T6 / $T;
    $I_2_4_2 = 2 * $P7 / $P;
    return $I_2_4_1 + $I_2_4_1_1 + $I_2_4_2;
}

function hisoblash_2_5($S12, $S13) {
    return 3 * $S12 / $S13 * 100;
}

function hisoblash_3_1($T7, $TT) {
    return 8 * $T7 / $TT * 100;
}

function hisoblash_3_2($T8, $T9) {
    return 8 * $T8 / $T9 * 100;
}

function hisoblash_3_3($Y1, $Y) {
    return 8 * $Y1 / $Y * 100;
}

function hisoblash_3_4($T, $T10, $T11) {
    $I_3_4_1 = 2 * $T10 / $T;
    $I_3_4_2 = $T11 / $T;
    return ($I_3_4_1 + $I_3_4_2) * 100;
}

function hisoblash_4_1($S4_1, $T) {
    return 2 * $S4_1 / $T * 100;
}

function hisoblash_4_2($S4_2, $T) {
    return $S4_2 / $T * 100;
}

// Ma'lumotlarni kiritish va hisoblash
$P = floatval(readline("Asosiy shtatdagi professor-o'qituvchilar soni: "));
$P1 = floatval(readline("Xorijiy OTMlarda ilmiy darajani olgan professor-o'qituvchilar soni: "));
$P2 = floatval(readline("Xorijiy OTMlarda o'quv mashg'ulotlari o'tkazgan professor-o'qituvchilar soni: "));
$P3 = floatval(readline("Fan doktori (DSc) ilmiy darajasiga ega professor-o'qituvchilar soni: "));
$P4 = floatval(readline("Fan nomzodi yoki falsafa doktori (PhD) ilmiy darajasiga ega professor-o'qituvchilar soni: "));
$D = floatval(readline("Hisobot yilida himoya qilingan doktorlik dissertatsiyalar soni: "));
$S1 = floatval(readline("Xalqaro indekslar ma'lumotlariga ko'ra iqtiboslar soni: "));
$S2 = floatval(readline("Xalqaro jurnallardagi ilmiy maqolalar soni: "));
$S3 = floatval(readline("Respublika jurnallaridagi ilmiy maqolalar soni: "));
$S4 = floatval(readline("Xorijiy ilmiy fondlar va tadqiqot markazlarining grantlari va buyurtmalari asosida olingan mablag'lar: "));
$S5 = floatval(readline("Sohalar buyurtmalari asosida o'tkazilgan tadqiqotlardan olingan mablag'lar: "));
$S6 = floatval(readline("Davlat grantlari asosida o'tkazilgan tadqiqotlardan olingan mablag'lar: "));
$P_pu = floatval(readline("Test sinovlariga jalb qilingan professor-o'qituvchilar soni: "));
$P5 = floatval(readline("Xorijiy tillar bo'yicha ijobiy baholangan professor-o'qituvchilar soni: "));
$P6 = floatval(readline("AKT bo'yicha ijobiy baholangan professor-o'qituvchilar soni: "));
$S7 = floatval(readline("Chop etilgan monografiyalar soni: "));
$S8 = floatval(readline("Intellektual mulk uchun himoyalangan hujjatlar (patentlar) soni: "));
$S9 = floatval(readline("AKT ga oid dasturlar va elektron bazalari uchun olingan guvohnomalar soni: "));

// Natijalarni chiqarish
echo "1.1: " . number_format(hisoblash_1_1($P, $P1), 2) . "\n";
echo "1.2: " . number_format(hisoblash_1_2($P, $P2), 2) . "\n";
echo "1.3: " . number_format(hisoblash_1_3($P, $P3, $P4), 2) . "\n";
echo "1.4: " . number_format(hisoblash_1_4($P, $D), 2) . "\n";
echo "1.5: " . number_format(hisoblash_1_5($P, $S1), 2) . "\n";
echo "1.6: " . number_format(hisoblash_1_6($P, $S2, $S3), 2) . "\n";
echo "1.7: " . number_format(hisoblash_1_7($P, $S4, $S5, $S6), 2) . "\n";
echo "1.8: " . number_format(hisoblash_1_8($P_pu, $P5, $P6), 2) . "\n";
echo "1.9: " . number_format(hisoblash_1_9($P, $S7, $S8, $S9), 2) . "\n";

// 2.1 dan 4.2 gacha bo'lgan yo'nalishlar uchun ham shu kabi kod yoziladi
// Har bir yo'nalish uchun kerakli ma'lumotlarni so'rab, tegishli funksiyani chaqirish kerak

?>
