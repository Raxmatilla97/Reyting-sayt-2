<!-- Dublikatlar statistikasi -->
<div class="grid grid-cols-3 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">
    <!-- Aktiv Table 11 statistikasi -->
    <div style="background: linear-gradient(to right, rgb(225, 29, 72), rgb(190, 18, 60)); overflow: hidden; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); border-radius: 0.5rem;">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt style="font-size: 0.875rem; font-weight: 500; color: rgb(255, 228, 230); text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                            Aktiv Table 11
                        </dt>
                        <dd class="flex items-baseline">
                            <div style="font-size: 1.5rem; font-weight: 600; color: white;">
                                {{ count($table11Duplicates) }}
                            </div>
                            <div style="margin-left: 0.5rem; font-size: 0.875rem; color: rgb(255, 228, 230);">
                                guruh
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div style="background-color: rgb(159, 18, 57); padding: 1.25rem 1.25rem 0.75rem;">
            <div style="font-size: 0.875rem;">
                <span style="color: rgb(255, 228, 230);">Yozuvlar: </span>
                <span style="font-weight: 500; color: white;">
                    {{ collect($table11Duplicates)->sum(function($duplicate) { return count($duplicate['records']); }) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Aktiv Table 20 statistikasi -->
    <div style="background: linear-gradient(to right, rgb(217, 119, 6), rgb(180, 83, 9)); overflow: hidden; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); border-radius: 0.5rem;">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt style="font-size: 0.875rem; font-weight: 500; color: rgb(254, 243, 199); text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                            Aktiv Table 20
                        </dt>
                        <dd class="flex items-baseline">
                            <div style="font-size: 1.5rem; font-weight: 600; color: white;">
                                {{ count($table20Duplicates) }}
                            </div>
                            <div style="margin-left: 0.5rem; font-size: 0.875rem; color: rgb(254, 243, 199);">
                                guruh
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div style="background-color: rgb(146, 64, 14); padding: 1.25rem 1.25rem 0.75rem;">
            <div style="font-size: 0.875rem;">
                <span style="color: rgb(254, 243, 199);">Yozuvlar: </span>
                <span style="font-weight: 500; color: white;">
                    {{ collect($table20Duplicates)->sum(function($duplicate) { return count($duplicate['records']); }) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Tuzatilgan dublikatlar -->
    <div style="background: linear-gradient(to right, rgb(5, 150, 105), rgb(4, 120, 87)); overflow: hidden; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); border-radius: 0.5rem;">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt style="font-size: 0.875rem; font-weight: 500; color: rgb(209, 250, 229); text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                            Tuzatilgan
                        </dt>
                        <dd class="flex items-baseline">
                            <div style="font-size: 1.5rem; font-weight: 600; color: white;">
                                {{ count($table11FixedDuplicates) + count($table20FixedDuplicates) }}
                            </div>
                            <div style="margin-left: 0.5rem; font-size: 0.875rem; color: rgb(209, 250, 229);">
                                guruh
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div style="background-color: rgb(6, 95, 70); padding: 1.25rem 1.25rem 0.75rem;">
            <div style="font-size: 0.875rem;">
                <span style="color: rgb(209, 250, 229);">Muvaffaqiyat</span>
            </div>
        </div>
    </div>

    <!-- Jami dublikat ballar -->
    <div style="background: linear-gradient(to right, rgb(79, 70, 229), rgb(67, 56, 202)); overflow: hidden; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); border-radius: 0.5rem;">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-star" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt style="font-size: 0.875rem; font-weight: 500; color: rgb(224, 231, 255); text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                            Dublikat ballar
                        </dt>
                        <dd class="flex items-baseline">
                            <div style="font-size: 1.5rem; font-weight: 600; color: white;">
                                {{ collect($table11Duplicates)->sum('total_points') + collect($table20Duplicates)->sum('total_points') }}
                            </div>
                            <div style="margin-left: 0.5rem; font-size: 0.875rem; color: rgb(224, 231, 255);">
                                ball
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div style="background-color: rgb(55, 48, 163); padding: 1.25rem 1.25rem 0.75rem;">
            <div style="font-size: 0.875rem;">
                <span style="color: rgb(224, 231, 255);">Aktiv ortiqcha</span>
            </div>
        </div>
    </div>

    <!-- Foydalanuvchilar soni -->
    <div style="background: linear-gradient(to right, rgb(8, 145, 178), rgb(14, 116, 144)); overflow: hidden; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1); border-radius: 0.5rem;">
        <div class="p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-users" style="color: white; font-size: 1.5rem;"></i>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt style="font-size: 0.875rem; font-weight: 500; color: rgb(207, 250, 254); text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">
                            Ta'sirlangan
                        </dt>
                        <dd class="flex items-baseline">
                            <div style="font-size: 1.5rem; font-weight: 600; color: white;">
                                {{ 
                                    collect($table11Duplicates)->pluck('user_id')
                                    ->merge(collect($table20Duplicates)->pluck('user_id'))
                                    ->merge(collect($table11FixedDuplicates)->pluck('user_id'))
                                    ->merge(collect($table20FixedDuplicates)->pluck('user_id'))
                                    ->unique()
                                    ->count() 
                                }}
                            </div>
                            <div style="margin-left: 0.5rem; font-size: 0.875rem; color: rgb(207, 250, 254);">
                                kishi
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>
        <div style="background-color: rgb(14, 116, 144); padding: 1.25rem 1.25rem 0.75rem;">
            <div style="font-size: 0.875rem;">
                <span style="color: rgb(207, 250, 254);">Jami</span>
            </div>
        </div>
    </div>
</div>

<!-- Ogohlantirish xabari -->
@if(count($table11Duplicates) > 0 || count($table20Duplicates) > 0)
<div style="background-color: rgb(255, 251, 235); border-left: 4px solid rgb(245, 158, 11); padding: 1rem; margin-bottom: 1.5rem;">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle" style="color: rgb(245, 158, 11); font-size: 1.25rem;"></i>
        </div>
        <div class="ml-3">
            <p style="font-size: 0.875rem; color: rgb(146, 64, 14);">
                <strong>Diqqat!</strong> {{ count($table11Duplicates) + count($table20Duplicates) }} ta aktiv dublikat topildi. 
                Automatik tuzatish jarayoni eng yangi yozuvni saqlab qoladi va qolganlarini 0 balga o'tkazadi.
                Amal bajarilishidan oldin barcha ma'lumotlarni tekshiring.
            </p>
        </div>
    </div>
</div>
@elseif(count($table11FixedDuplicates) > 0 || count($table20FixedDuplicates) > 0)
<div style="background-color: rgb(236, 253, 245); border-left: 4px solid rgb(16, 185, 129); padding: 1rem; margin-bottom: 1.5rem;">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-check-circle" style="color: rgb(16, 185, 129); font-size: 1.25rem;"></i>
        </div>
        <div class="ml-3">
            <p style="font-size: 0.875rem; color: rgb(6, 95, 70);">
                <strong>Ahvol yaxshi!</strong> Hozirda hech qanday aktiv dublikat yo'q. 
                {{ count($table11FixedDuplicates) + count($table20FixedDuplicates) }} ta dublikat tuzatilgan.
                Tuzatilgan dublikatlarni "Tuzatilgan dublikatlar" bo'limida ko'rishingiz mumkin.
            </p>
        </div>
    </div>
</div>
@else
<div style="background-color: rgb(239, 246, 255); border-left: 4px solid rgb(59, 130, 246); padding: 1rem; margin-bottom: 1.5rem;">
    <div class="flex">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle" style="color: rgb(59, 130, 246); font-size: 1.25rem;"></i>
        </div>
        <div class="ml-3">
            <p style="font-size: 0.875rem; color: rgb(30, 64, 175);">
                <strong>Ma'lumot!</strong> Hozirda hech qanday dublikat topilmadi. Tizim toza holatda.
            </p>
        </div>
    </div>
</div>
@endif