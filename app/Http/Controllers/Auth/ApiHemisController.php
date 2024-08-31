<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
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

        $employeeProvider = $this->getEmployeeProvider();
        $authorizationUrl = $employeeProvider->getAuthorizationUrl();

        return redirect()->away($authorizationUrl);
    }

    public function handleAuthorizationCallback(Request $request)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        try {
            $code = $request->query('code');
            $state = $request->query('state');

            if (!$code) {
                throw new \Exception("HEMIS API dan code qabul qilinmadi!");
            }

            $employeeProvider = $this->getEmployeeProvider();

            $accessToken = $employeeProvider->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            $resourceOwner = $employeeProvider->getResourceOwner($accessToken);
            $userDetails = $resourceOwner->toArray();

            $this->validateUserDetails($userDetails);

            $user = $this->findOrCreateUser($userDetails);

            if (!$user) {
                throw new \Exception("Foydalanuvchi yaratishda xatolik yuz berdi.");
            }

            Auth::login($user);
            return $this->redirectUserBasedOnRole($user);
        } catch (\Exception $e) {
            \Log::error('Login jarayonida xatolik: ' . $e->getMessage());
            return redirect('/login')->withErrors(['oauth_error' => $e->getMessage()]);
        }
    }

    private function getEmployeeProvider()
    {
        return new GenericProvider([
            'clientId'                => env('EMPLOYEE_CLIENT_ID'),
            'clientSecret'            => env('EMPLOYEE_CLIENT_SECRET'),
            'redirectUri'             => env('EMPLOYEE_REDIRECT_URI'),
            'urlAuthorize'            => env('EMPLOYEE_URL_AUTHORIZE'),
            'urlAccessToken'          => env('EMPLOYEE_URL_ACCESS_TOKEN'),
            'urlResourceOwnerDetails' => env('EMPLOYEE_URL_RESOURCE_OWNER_DETAILS')
        ]);
    }

    private function validateUserDetails($userDetails)
    {
        $requiredFields = ['employee_id_number', 'login', 'uuid', 'employee_id', 'type', 'email'];
        foreach ($requiredFields as $field) {
            if (!isset($userDetails[$field])) {
                throw new \Exception("Kerakli maydon topilmadi: $field");
            }
        }
    }

    private function findOrCreateUser($userDetails)
    {
        // dd($userDetails);
        $user = User::where('employee_id_number', $userDetails['employee_id_number'])->first();

        if (!$user) {
            $employeeData = $this->getEmployeeDataFromHemis($userDetails['employee_id_number']);
            $departmentId = $this->getDepartmentId($userDetails['departments']);

            if (!$departmentId) {
                throw new \Exception('Siz Hemis tizimida hechqaysi kafedraga birlashtirilmagansiz!');
            }

            $fileName = $this->saveUserImage($userDetails['picture']);

            // Email maydonini tekshirish va standart qiymat berish
            $email = !empty($userDetails['email']) ? $userDetails['email'] : $userDetails['employee_id_number'] . '@cspu.uz';

            $user = User::create([
                'employee_id_number' => $userDetails['employee_id_number'],
                'name' => $employeeData['first_name'],
                'first_name' => $employeeData['first_name'],
                'second_name' => $employeeData['second_name'],
                'third_name' => $employeeData['third_name'],
                'gender_code' => $employeeData['gender']['code'],
                'gender_name' => $employeeData['gender']['name'],
                'birth_date' => Date::parse($employeeData['birth_date'])->format('Y-m-d'),
                'image' => $fileName,
                'year_of_enter' => $employeeData['year_of_enter'],
                'academicDegree_code' => $employeeData['academicDegree']['code'],
                'academicDegree_name' => $employeeData['academicDegree']['name'],
                'academicRank_code' => $employeeData['academicRank']['code'],
                'academicRank_name' => $employeeData['academicRank']['name'],
                'department_id' => $departmentId,
                'login' => $userDetails['login'],
                'uuid' => $userDetails['uuid'],
                'employee_id' => $userDetails['employee_id'],
                'user_type' => $userDetails['type'],
                'email' => $email,
                'phone' => $userDetails['phone'],
                'password' => Hash::make(Str::random(16)),  // Xavfsiz tasodifiy parol
                'status' => 1,
            ]);
        }

        return $user;
    }

    private function getEmployeeDataFromHemis($employeeIdNumber)
    {
        $url = env('API_HEMIS_URL') . "/rest/v1/data/employee-list?type=all&search=$employeeIdNumber";
        $response = Http::withToken(env('API_HEMIS_TOKEN'))->get($url)->json();

        if ($response['data']['pagination']['totalCount'] > 0) {
            return $response['data']['items'][0];
        }

        throw new \Exception("HEMIS dan xodim ma'lumotlari olinmadi.");
    }

    private function getDepartmentId($departments)
    {
        foreach ($departments as $department) {
            if (isset($department['employmentForm']['code']) && $department['employmentForm']['code'] == 11) {
                $departmentId = $department['department']['id'];
                if (Department::where('id', $departmentId)->exists()) {
                    return $departmentId;
                }
            }
        }
        return null;
    }

    private function saveUserImage($imageUrl)
    {
        if (!$imageUrl) {
            return null;
        }

        $imageContent = file_get_contents($imageUrl);
        $fileName = 'image_' . time() . '_' . uniqid() . '.jpg';
        $storagePath = storage_path('app/public/users/image/') . $fileName;

        if (!file_put_contents($storagePath, $imageContent)) {
            \Log::error('Foydalanuvchi rasmini saqlashda xatolik');
            return null;
        }

        return $fileName;
    }

    private function redirectUserBasedOnRole($user)
    {
        // Foydalanuvchi rolini tekshirish va tegishli sahifaga yo'naltirish
        // Bu yerda foydalanuvchi rolini aniqlaydigan mantiq bo'lishi kerak
        if ($user->isAdmin()) {
            return redirect()->route('murojatlar.list');
        } else {
            return redirect()->route('dashboard');
        }
    }
}
