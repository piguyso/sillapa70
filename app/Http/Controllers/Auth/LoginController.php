<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Session;
use Auth;
use App\User;
use App\systemactives;
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

      protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
	public function authenticated($request, $user )
    {

        $center = systemactives::where('systemname','=','center')->get();
        $score = systemactives::where('systemname','=','score')->get();

                session(['year' => $request->year]);
                session(['position' => Auth::user()->position]);
                session(['name' => Auth::user()->name]);
                session(['lastname' => Auth::user()->lastname]);
                session(['userid' => Auth::user()->id]);
                //$pos = Auth::user()->position;

                //session(['position'=>])
                //dd($pos);
                return redirect()->intended($this->redirectPath());
        
    }
}
