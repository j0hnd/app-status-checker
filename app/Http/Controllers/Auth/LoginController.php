<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommonRequest;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;


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

    use ThrottlesLogins;


    protected $maxAttempts = 3;

    protected $decayMinutes = 1;


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $userRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('guest')->except('logout');

        $this->userRepository = $userRepository;
    }

    public function index(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $remember_me = $request->has('remember_me') ? true : false;


        if (method_exists($this, 'hasTooManyLoginAttempts') and $this->hasTooManyLoginAttempts($request)) {
            $user = $this->userRepository->findByEmail($credentials['email']);

            // lock user account
            $user->is_locked = User::USER_ACCOUNT_LOCKED;
            $user->save();

            $this->fireLockoutEvent($request);

            return $this->sendLoginResponse($request);
        } else {
            $credentials['is_locked'] = User::USER_ACCOUNT_NOT_LOCKED;
        }

        if (Auth::attempt($credentials, $remember_me)) {
            $request->session()->regenerate();

            $this->clearLoginAttempts($request);

            return response()->json([
                'success' => true,
                'redirect' => $this->redirectTo
            ], 200);
        }

        // increment login attempt
        $this->incrementLoginAttempts($request);

        $user = $this->userRepository->findByEmail($credentials['email']);

        // check if account is locked
        if ($user->is_locked == 1) {
            return response()->json([
                'success' => false,
                'message' => "Account is locked!"
            ], 503);
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function forgot_password(Request $request): JsonResponse
    {
        $response = ['success' => false];
        $http_code = 400;

        if ($request->ajax()) {
            $credential = $request->validate([
                'email' => ['required', 'email']
            ]);

            try {
                $user = $this->userRepository->findByEmail($credential['email']);

                if ($user) {
                    $temporary_password = Str::random(10);
                    $hashed_temporary_password = Hash::make($temporary_password);

                    $user->password = $hashed_temporary_password;

                    if ($user->save()) {
                        Mail::to($user->email)->send(new ResetPasswordMail($user, $temporary_password));

                        $response['success'] = true;
                        $http_code = 200;
                    }
                }
            } catch (\Exception $exception) {
                //
            }
        }

        return response()->json($response, $http_code);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
