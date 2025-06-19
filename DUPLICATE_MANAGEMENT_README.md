# Дубликатларни бошқариш тизими (v2.2)

## Умумий маълумот

Бу тизим Laravel илова учун дубликатларни аниқлаш ва тузатиш тизимидир. Тизим Table 11 ва Table 20 дубликатларини топиб, автоматик равишда тузатиш имконини беради.

## Янги хусусиятлар v2.2

### 1. 70% Ўхшашлик алгоритми
- **Table 11**: Мақола номи, журнал номи ва нашр йили бўйича 70% ўхшашлик
- **Table 20**: Журнал номи ва муаллифлар сони бўйича 70% ўхшашлик
- Levenshtein алгоритми ишлатилади

### 2. Автоматик балл обнуление
- Агар Table_11_1 га балл берилса, Table_11_2 ва Table_11_3 дан балл автоматик 0 бўлади
- Агар Table_11_2 га балл берилса, Table_11_1 ва Table_11_3 дан балл автоматик 0 бўлади
- Агар Table_11_3 га балл берилса, Table_11_1 ва Table_11_2 дан балл автоматик 0 бўлади
- Худди шундай Table 20 учун ҳам ишлайди

### 3. Вкладочный интерфейс
- **Актив дубликатлар**: Фақат `point > 0` бўлган дубликатлар
- **Тузатилган дубликатлар**: `point = 0` бўлган дубликатлар тарихи
- **Логлар**: Барча операциялар журнали

## Фойдаланиш

### Веб-интерфейс орқали
1. Админ сифатида кириш
2. `/admin/duplicate-management` га ўтиш
3. Дубликатларни кўриш ва тузатиш

### Artisan командалари орқали
```bash
# Тестовые дубликатлар яратиш
php artisan test:duplicates

# Тестовые маълумотларни тозалаш
php artisan test:clear-duplicates
```

## Техник тафсилотлар

### Ўхшашлик ҳисоблаш

#### Table 11 учун:
- **Журнал номи**: 40% оғирлик
- **Мақола номи**: 40% оғирлик  
- **Нашр йили**: 20% оғирлик
- **Минимал чегара**: 70%

#### Table 20 учун:
- **Журнал номи**: 80% оғирлик
- **Муаллифлар сони**: 20% оғирлик
- **Минимал чегара**: 70%

### Автоматик тузатиш қоидалари
1. Энг янги ёзув (created_at бўйича) сақланади
2. Қолган ёзувлар `point = 0` қилинади
3. Барча операциялар логланади

### Event Listeners
- `PointUserDeportament` моделида `updated` event
- Фақат `point` майдони ўзгарганда ишлайди
- Фақат `point > 0` бўлганда ишлайди
- Бир хил фойдаланувчи ва йил учун ишлайди

## Хавфсизлик

- CSRF токенлар
- `isadmin` middleware
- База маълумотлари транзакциялари
- Тўлиқ логлаш

## Файллар структураси

```
app/Http/Controllers/
├── DuplicateManagementController.php

resources/views/dashboard/
├── duplicate-management.blade.php
└── partials/
    ├── duplicate-stats.blade.php
    ├── duplicate-table11.blade.php
    ├── duplicate-table20.blade.php
    ├── duplicate-table11-fixed.blade.php
    ├── duplicate-table20-fixed.blade.php
    └── duplicate-logs.blade.php

app/Console/Commands/
├── TestDuplicatesCommand.php
└── ClearTestDataCommand.php

app/Models/
├── PointUserDeportament.php (Event Listeners қўшилди)
└── Tables/
    ├── Table_11_1_.php
    ├── Table_11_2_.php
    ├── Table_11_3_.php
    ├── Table_20_1_.php
    ├── Table_20_2_.php
    └── Table_20_3_.php
```

## Роутлар

```php
// Админ роутлари
Route::middleware(['isadmin'])->group(function () {
    Route::get('/admin/duplicate-management', [DuplicateManagementController::class, 'index']);
    Route::post('/admin/duplicate-management/fix-table11', [DuplicateManagementController::class, 'fixTable11Duplicates']);
    Route::post('/admin/duplicate-management/fix-table20', [DuplicateManagementController::class, 'fixTable20Duplicates']);
    Route::post('/admin/duplicate-management/fix-single', [DuplicateManagementController::class, 'fixSingleDuplicate']);
});

// Тест роути
Route::get('/test-duplicate-management', [DuplicateManagementController::class, 'index']);
```

## Муаммоларни ҳал қилиш

### Дубликатлар кўрсатилмаяпти
1. Маълумотлар базасида Table 11/20 маълумотлари борлигини текшириш
2. `point > 0` бўлган ёзувлар борлигини текшириш
3. Тестовые маълумотлар яратиш: `php artisan test:duplicates`

### Автоматик обнуление ишламаяпти
1. `PointUserDeportament` модели Event Listeners ишлашини текшириш
2. Логларни текшириш: `storage/logs/laravel.log`
3. Бир хил `user_id` ва `year` бўлишини текшириш

### Хатолар
- PHP версиясини текшириш (>=8.2)
- Composer зависимостларини янгилаш
- Fillable майдонлар мавжудлигини текшириш

## Тестлаш

### Тестовые маълумотлар яратиш
```bash
php artisan test:duplicates
```

### Тестовые маълумотларни тозалаш
```bash
php artisan test:clear-duplicates
```

### Қўлда тестлаш
1. Тестовые дубликатлар яратиш
2. `/admin/duplicate-management` га ўтиш
3. Дубликатлар кўрсатилишини текшириш
4. Автоматик тузатишни синаш
5. Балл берганда автоматик обнуление ишлашини текшириш

## Кейинги ривожлантириш

- Бошқа таблицалар учун дубликат аниқлаш
- Email билдиришнома
- Экспорт функциялари
- Статистика дашборди

## Муаллиф

Тизим Laravel фреймворкида ишлаб чиқилган ва дубликатларни аниқлаш учун Levenshtein алгоритмидан фойдаланади.

## Хусусиятлар

### 🔍 Дубликатларни топиш
- **Table 11**: Журнал номи, мақола номи ва нашр йили бўйича ўхшашлик
- **Table 20**: Журнал номи ва муаллифлар сони бўйича ўхшашлик
- **Levenshtein алгоритми**: 70% ўхшашлик босқичи
- **UTF-8 қўллаб-қувватлаш**: Ўзбек ва инглиз матнлари

### 📊 Статистика
- Топилган дубликатлар сони
- Жами ортиқча баллар
- Таъсирланган фойдаланувчилар сони
- Детали ўхшашлик баллари

### 🛠️ Автоматик тузатиш
- Энг янги ёзувни сақлаш
- Эски ёзувларни 0 балга ўтказиш
- Барча амалларни логлаш
- Хавфсизлик тасдиқлари

## Роутлар

### Админ роутлари (middleware: isadmin)
```php
/admin/duplicate-management              // Асосий саҳифа
/admin/duplicate-management/fix-table11  // Table 11 тузатиш
/admin/duplicate-management/fix-table20  // Table 20 тузатиш
/admin/duplicate-management/fix-single   // Битта ёзувни тузатиш
```

### Тест роути (middleware йўқ)
```php
/test-duplicate-management               // Тест учун
```

## Файллар тузилиши

### Контроллер
```
app/Http/Controllers/DuplicateManagementController.php
```

### Blade шаблонлар
```
resources/views/dashboard/duplicate-management.blade.php
resources/views/dashboard/partials/duplicate-stats.blade.php
resources/views/dashboard/partials/duplicate-table11.blade.php
resources/views/dashboard/partials/duplicate-table20.blade.php
resources/views/dashboard/partials/duplicate-logs.blade.php
```

### Artisan командалар
```
app/Console/Commands/TestDuplicatesCommand.php     // Тест маълумотлар
app/Console/Commands/ClearTestDataCommand.php      // Тозалаш
```

## Фойдаланиш

### 1. Тизимга кириш
- Админ ҳуқуқлари керак (`is_admin = 1`)
- Ёки тест роутидан фойдаланинг

### 2. Дубликатларни кўриш
- Статистика карточкалари
- Детали жадваллар
- Ўхшашлик баллари

### 3. Тузатиш
- **Барчасини тузатиш**: Автоматик режим
- **Битта тузатиш**: Қўлда танлаш
- **Кўриш**: Детали маълумот

## Тест маълумотлар

### Тест дубликатлар яратиш
```bash
php artisan test:duplicates
```

### Тест маълумотларни тозалаш
```bash
php artisan test:clear-duplicates
```

## Ўхшашлик алгоритми

### Table 11 учун
1. **Журнал номи ўхшашлиги** (33%)
2. **Мақола номи ўхшашлиги** (33%)
3. **Йил ўхшашлиги** (34%)

Умумий ўхшашлик ≥ 70% бўлса дубликат деб ҳисобланади.

### Table 20 учун
1. **Журнал номи ўхшашлиги** (50%)
2. **Муаллифлар сони ўхшашлиги** (50%)

Умумий ўхшашлик ≥ 70% бўлса дубликат деб ҳисобланади.

## Хавфсизлик

### Логлаш
- Барча амаллар логланади
- Вақт белгиси
- Админ ID
- Таъсирланган маълумотлар

### Тасдиқлаш
- JavaScript confirm диалоглари
- Қайтариб олиш имкони йўқ
- Фақат админлар учун

## Техник деталлар

### Модел боғланишлар
```php
PointUserDeportament::with(['employee'])
    ->whereNotNull('table_11_1_id')
    ->orWhereNotNull('table_11_2_id')
    // ...
```

### Ўхшашлик ҳисоблаш
```php
private function calculateSimilarity($str1, $str2)
{
    // Levenshtein distance алгоритми
    // UTF-8 қўллаб-қувватлаш
    // Нормаллаштириш
}
```

### Автоматик тузатиш логикаси
1. Дубликатларни топиш
2. Санаси бўйича тартиблаш (янгиси биринчи)
3. Биринчисини сақлаш
4. Қолганларини 0 балга ўтказиш
5. Логлаш

## Хатоларни ҳал қилиш

### 404 хатоси
- `is_admin = 1` текшириш
- Middleware sozlamalarini текшириш
- Тест роутидан фойдаланиш

### Дубликатлар топилмаса
- Тест маълумотлар яратиш
- Ўхшашлик босқичини текшириш (70%)
- Маълумотлар форматини текшириш

### Автоматик тузатиш ишламаса
- Database транзакцияларни текшириш
- Логларни кўриш
- Модел боғланишларни текшириш

## Кейинги ривожлантириш

### Қўшилиши мумкин
- Қўлда ўхшашлик босқичи созлаш
- Кўпроқ жадваллар қўллаб-қувватлаш
- Email билдиришнома
- API endpoints
- Батч операциялар

### Оптимизация
- Database индекслар
- Кеш қўллаб-қувватлаш
- Фон ишлов бериш
- Прогресс индикатори

---

**Муаллиф**: Дубликатларни бошқариш тизими  
**Версия**: 1.0  
**Сана**: 2024 