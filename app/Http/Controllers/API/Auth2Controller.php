<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use League\OAuth2\Client\Provider\GenericProvider;
use App\Models\User;

class Auth2Controller extends Controller
{

    public function redirectToAuthorization(Request $request)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }

        $redirectUrl = route("handleAuthorizationCallback");

        $clientId = Config::get('app.employee_oauth.CLIENT_ID');
        $clientSecret = Config::get('app.employee_oauth.CLIENT_SECRET');
        $redirectUri = Config::get('app.employee_oauth.REDIRECT_URI');
        $urlAuthorize = Config::get('app.employee_oauth.URL_AUTHORIZE');
        $urlAccessToken = Config::get('app.employee_oauth.URL_ACCESS_TOKEN');
        $urlResourceOwnerDetails = Config::get('app.employee_oauth.URL_RESOURCE_OWNER_DETAILS');
        
        $employeeProvider = new GenericProvider([
            'clientId'                => $clientId,
            'clientSecret'            => $clientSecret,
            'redirectUri'             => $redirectUri,
            'urlAuthorize'            => $urlAuthorize,
            'urlAccessToken'          => $urlAccessToken,
            'urlResourceOwnerDetails' => $urlResourceOwnerDetails
        ]);
        // dd($employeeProvider);

        // Редирект на страницу авторизации OAuth2
        $authorizationUrl = $employeeProvider->getAuthorizationUrl();
        return redirect()->away($authorizationUrl);
    }

public function handleAuthorizationCallback(Request $request)
    {
        if (Auth::check()) {
            return redirect('/dashboard');
        }
        $clientId = Config::get('app.employee_oauth.CLIENT_ID');
        $clientSecret = Config::get('app.employee_oauth.CLIENT_SECRET');
        $redirectUri = Config::get('app.employee_oauth.REDIRECT_URI');
        $urlAuthorize = Config::get('app.employee_oauth.URL_AUTHORIZE');
        $urlAccessToken = Config::get('app.employee_oauth.URL_ACCESS_TOKEN');
        $urlResourceOwnerDetails = Config::get('app.employee_oauth.URL_RESOURCE_OWNER_DETAILS');

        $employeeProvider = new GenericProvider([
            'clientId'                => $clientId,
            'clientSecret'            => $clientSecret,
            'redirectUri'             => $redirectUri,
            'urlAuthorize'            => $urlAuthorize,
            'urlAccessToken'          => $urlAccessToken,
            'urlResourceOwnerDetails' => $urlResourceOwnerDetails
        ]);

       

        if ($request->has('code')) {
            try {
                $accessToken = $employeeProvider->getAccessToken('authorization_code', [
                    'code' => $request->input('code')
                ]);

                // Получение информации о пользователе с помощью полученного токена
                $resourceOwner = $employeeProvider->getResourceOwner($accessToken);

                // Данные пользователя из OAuth2
                $userDetails = $resourceOwner->toArray();

//           	dd$userDetails);


                // Пример проверки наличия идентификатора пользователя
                if (isset($userDetails['employee_id_number'])) {
                    // Ваша логика проверки или создания пользователя в системе Laravel
                    $user = User::where('employee_id_number', $userDetails['employee_id_number'])->first();

                    if (!$user) {
                        $employee_id_number = $userDetails['employee_id_number'];
                        $url = "https://hemis.cspi.uz/rest/v1/data/employee-list?type=all&limit=200&search=$employee_id_number";
                        $response = Http::withToken("token")->get($url)->json();


                        if ($response['data']['pagination']['totalCount'] > 0){

                            foreach ($response['data']['items'] as $item){
                                $birth_date = Date::parse($item["birth_date"])->format('Y-m-d');
                                $contract_date = Date::parse($item["contract_date"])->format('Y-m-d');
                                $decree_date = Date::parse($item["decree_date"])->format('Y-m-d');

                                $fileName = '';

                                if ($userDetails["picture"]) {
                                    $imageContent = file_get_contents($userDetails["picture"]);

                                    // Generate a unique file name or use some logic to create a unique name
                                    $fileName = 'image_' . time() . '_' . uniqid() . '.jpg';

                                    // Specify the storage path where you want to save the image
                                    $storagePath = storage_path('app/public/users/image/') . $fileName;

                                    // Save the downloaded image to the storage path
                                    file_put_contents($storagePath, $imageContent);
                                }


                                $user_save = User::updateOrCreate(
                                    ['employee_id_number' => $userDetails["employee_id_number"]],
                                    [
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
                                        "department_id" => $item["department"]['id'],
                                        "login" => $userDetails['login'],
                                        "uuid" => $userDetails['uuid'],
                                        "employee_id" => $userDetails['employee_id'],
                                        "user_type" => $userDetails['type'],
                                        "email" => $userDetails['email'],
                                        "phone" => $userDetails['phone'],
                                        "password" => Hash::make($userDetails["employee_id_number"]),
                                    ]
                                );
                                
                            }
                        }
                        Auth::login($user_save);
                        return redirect(route('admin.dashboard'));
                    }
                    Auth::login($user);
                    return redirect(route('admin.dashboard'));
                } else {
                    return redirect('/login')->withErrors(['oauth_error' => 'Ошибка аутентификации']);
                }
            } catch (\Exception $e) {
                return redirect('/login')->withErrors(['oauth_error' => 'Ошибка аутентификации']);
            }
        } else {
            // Если нет кода, возможно это попытка доступа без авторизации
            return redirect('/login');
        }
    }
}
