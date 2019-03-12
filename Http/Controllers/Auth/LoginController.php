<?php

namespace Modules\Cockpit\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/cockpit';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('cockpit::auth.login');
    }

    // protected function credentials(Request $request)
    // {
    //     return ['email' => $request->email , 'password' => $request->password];
    //     //return $request->only($this->username(), 'password');
    // }

    protected function authenticated(Request $request, $user)
    {
        if($user->is_active == 0){
            $this->logout($request);
            $validator = Validator::make([],[]);
            $validator->errors()->add('user_not_active', '使用者尚未啟用，請等待管理員開啟權限後再登入。');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user->last_login = date('Y-m-d H:i:s');
        $user->save();
    }
}
