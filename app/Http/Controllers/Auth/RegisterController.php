<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\FirebaseException;
use Google\Cloud\Core\Timestamp;
use DateTime;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    protected $auth;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    public function __construct(FirebaseAuth $auth) {
        $this->middleware('guest');
        $this->auth = $auth;
    }
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    protected function register(Request $request) {
        $this->validator($request->all())->validate();
        $userProperties = [
            'email' => $request->input('email'),
            'emailVerified' => false,
            'password' => $request->input('password'),
            'displayName' => $request->input('name'),
            'disabled' => false,
        ];
        $createdUser = $this->auth->createUser($userProperties);
        // dd($createdUser);
        // firestoreにもユーザー情報を登録しておく
        $now = new Timestamp(new DateTime());
        $stuRef = app('firebase.firestore')->database()->collection('users')->document($createdUser->uid);
        $stuRef->set([
            "uid" => $createdUser->uid,
            "name" => $createdUser->displayName,
            "fname" => '',
            "lname" => '',
            "email" => $createdUser->email,
            "createdAt" => $now,
            "updatedAt"=> $now,
            "userImg" => null
        ]);
        return redirect()->route('login');
    }
}


// 以下、$createdUser が持つ値
// Kreait\Firebase\Auth\UserRecord {#434 ▼
//     +uid: "LnMEBLEGpeRHM26c8gljC6nDJU72"
//     +email: "test3@gimal.com"
//     +emailVerified: false
//     +displayName: "テスト"
//     +photoUrl: null
//     +phoneNumber: null
//     +disabled: false
//     +metadata: Kreait\Firebase\Auth\UserMetaData {#403 ▶}
//     +providerData: array:1 [▶]
//     +passwordHash: "UkVEQUNURUQ="
//     +passwordSalt: null
//     +customClaims: []
//     +tokensValidAfterTime: DateTimeImmutable @1620530434 {#416 ▶}
//     +tenantId: null
//   }
