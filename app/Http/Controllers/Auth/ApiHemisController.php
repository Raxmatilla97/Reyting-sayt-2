<?php


namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use League\OAuth2\Client\Provider\GenericProvider;
use App\Models\User;
use App\Models\Department;

class ApiHemisController extends Controller
{

    public function redirectToAuthorization(Request $request)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        $redirectUrl = route("handleAuthorizationCallback");

        $clientId = env('EMPLOYEE_CLIENT_ID');
        $clientSecret = env('EMPLOYEE_CLIENT_SECRET');
        $redirectUri = env('EMPLOYEE_REDIRECT_URI');
        $urlAuthorize = env('EMPLOYEE_URL_AUTHORIZE');
        $urlAccessToken = env('EMPLOYEE_URL_ACCESS_TOKEN');
        $urlResourceOwnerDetails = env('EMPLOYEE_URL_RESOURCE_OWNER_DETAILS');

        $employeeProvider = new GenericProvider([
            'clientId'                => $clientId,
            'clientSecret'            => $clientSecret,
            'redirectUri'             => $redirectUri,
            'urlAuthorize'            => $urlAuthorize,
            'urlAccessToken'          => $urlAccessToken,
            'urlResourceOwnerDetails' => $urlResourceOwnerDetails
        ]);


        // OAuth2 sahifssiga redirect qilish
        $authorizationUrl = $employeeProvider->getAuthorizationUrl();

        return redirect()->away($authorizationUrl);
    }

    public function handleAuthorizationCallback(Request $request)
    {

        $url_full = "http://127.0.0.1:8000/" . $request->getRequestUri();

        $parsed_url = parse_url($url_full);
        parse_str($parsed_url['query'], $params);
        $code = $params['code'];
        $state = $params['state'];


        if (Auth::check()) {
            return redirect('/dashboard');
        }

        $clientId = env('EMPLOYEE_CLIENT_ID');
        $clientSecret = env('EMPLOYEE_CLIENT_SECRET');
        $redirectUri = env('EMPLOYEE_REDIRECT_URI');
        $urlAuthorize = env('EMPLOYEE_URL_AUTHORIZE');
        $urlAccessToken = env('EMPLOYEE_URL_ACCESS_TOKEN');
        $urlResourceOwnerDetails = env('EMPLOYEE_URL_RESOURCE_OWNER_DETAILS');

        $employeeProvider = new GenericProvider([
            'clientId'                => $clientId,
            'clientSecret'            => $clientSecret,
            'redirectUri'             => $redirectUri,
            'urlAuthorize'            => $urlAuthorize,
            'urlAccessToken'          => $urlAccessToken,
            'urlResourceOwnerDetails' => $urlResourceOwnerDetails
        ]);



        if ($code) {

            try {

                $accessToken = $employeeProvider->getAccessToken('authorization_code', [
                    'code' => $code
                ]);


                // Token yordamida foydalanuvchi ma'lumotlarini olish
                $resourceOwner = $employeeProvider->getResourceOwner($accessToken);

                // OAuth2 dan foydalanuvchi ma'lumotlari
                $userDetails = $resourceOwner->toArray();

                // Foydalanuvchi id raqami yordamida uni tekshirish
                if (isset($userDetails['employee_id_number'])) {

                    // Tekshirish yoki yaratish logikasi kodlari bu yerdan boshlanadi
                    $user = User::where('employee_id_number', $userDetails['employee_id_number'])->first();

                    if (!$user) {

                        $employee_id_number = $userDetails['employee_id_number'];

                        $url = env('API_HEMIS_URL') . "/rest/v1/data/employee-list?type=all&search=$employee_id_number";
                        $response = Http::withToken(env('API_HEMIS_TOKEN'))->get($url)->json();

                        // Code 200 bo'lgach

                        if ($response['data']['pagination']['totalCount'] > 0) {

                            foreach ($response['data']['items'] as $item) {

                                $birth_date = Date::parse($item["birth_date"])->format('Y-m-d');
                                $contract_date = Date::parse($item["contract_date"])->format('Y-m-d');
                                $decree_date = Date::parse($item["decree_date"])->format('Y-m-d');

                                $fileName = '';

                                if ($userDetails["picture"]) {

                                    $imageContent = file_get_contents($userDetails["picture"]);
                                    $fileName = 'image_' . time() . '_' . uniqid() . '.jpg';
                                    $storagePath = storage_path('app/public/users/image/') . $fileName;

                                    // Agarda 'Xatolik 2' chiqsa Storage da users/image/ papkasi bo'lmagan bo'lishi mumkin yoki chmod 755-777 bo'lmagan bo'ladi.
                                    file_put_contents($storagePath, $imageContent);
                                }



                                // Yangi foydalanuvchi hemis orqali login amalga oshirayotganda uning departamentlari ichidan (employmentForm)
                                // dagi code ga qarab uni bo'lim, yoki kafedra, yoki fakultetda ishlayotganligini tasdiqlab olishimiz kerak!
                                // Buning uchun oddiy yechim sifatida userni asosiy ish  joyidan api codini olib undan aynan shu kafedra id raqamini olib ishlatmoqdamiz
                                // $department['employmentForm']['code'] == 11  bu 11 raqami asosiy ish joyi degani.

                                $department_id = null;
                                foreach ($userDetails['departments'] as $department) {

                                    if (isset($department['employmentForm']['code']) && $department['employmentForm']['code'] == 11) {
                                        $department_id = $department['department']['id'];

                                        break;
                                    }
                                }

                                // Test uchun cod bu keyin o'chirib yuboriladi!
                                // if (isset($department_id) &&  $department_id === 81) {
                                //     $department_id = 54;
                                // }

                                if (isset($department_id)) {
                                    // Foydalanuvchining departament_id sining mavjudligini tekshirish
                                    $isInDepartment = Department::where('id', $department_id)->exists();
                                }



                                if (!$isInDepartment) {
                                    Auth::logout(); // Foydalanuvchini logout qilish
                                    return redirect('/login')->withErrors(['oauth_error' => 'Siz Hemis tizimida hechqaysi kafedraga birlashtirilmagansiz!']);
                                }


                                $user_save = User::updateOrCreate(
                                    ['employee_id_number' => $userDetails["employee_id_number"]],
                                    [
                                        "employee_id_number" => $item["employee_id_number"],
                                        "name" => $item["first_name"],
                                        "first_name" => $item["first_name"],
                                        "second_name" => $item["second_name"],
                                        "third_name" => $item["third_name"],
                                        "gender_code" => $item["gender"]['code'],
                                        "gender_name" => $item["gender"]['name'],
                                        "birth_date" => $birth_date,
                                        "image" => $fileName,
                                        "year_of_enter" => $item["year_of_enter"],
                                        "academicDegree_code" => $item["academicDegree"]['code'],
                                        "academicDegree_name" => $item["academicDegree"]['name'],
                                        "academicRank_code" => $item["academicRank"]['code'],
                                        "academicRank_name" => $item["academicRank"]['name'],
                                        "department_id" => $department_id,
                                        "login" => $userDetails['login'],
                                        "uuid" => $userDetails['uuid'],
                                        "employee_id" => $userDetails['employee_id'],
                                        "user_type" => $userDetails['type'],
                                        "email" => $userDetails['email'],
                                        "phone" => $userDetails['phone'],
                                        "password" => Hash::make($userDetails["employee_id_number"]),
                                        "status" => 1,
                                    ]
                                );
                                // dd($user_save);



                            }
                        }

                        Auth::login($user_save);
                        return redirect(route('admin.dashboard'));
                    }

                    Auth::login($user);
                    return redirect(route('admin.dashboard'));
                } else {
                    return redirect('/login')->withErrors(['oauth_error' => 'Xatolik 1']);
                }
            } catch (\Exception $e) {
                \Log::error('There was an error: ' . $e->getMessage());
                return redirect('/login')->withErrors(['oauth_error' => 'Xatolik: №2 "Yani Storage da kerakli papkalar yaratilmagan bo\'lishi mumkin!"']);
            }
        } else {
            // Agar kod kelmasa bu avtarizatsiya qilinmaganini bildiradi
            return redirect('/login')->withErrors(['oauth_error' => "HEMIS API dan code qabul qilinmadi!"]);
        }
    }
}
