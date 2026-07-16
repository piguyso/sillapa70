<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserauthController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */


	public function authenticate(request $request)
		{
				     $user_data = array(
					  'email'  => $request->get('email'),
					  'password' => $request->get('password')
					 );

					 if(Auth::attempt($user_data))
					 {
					  return redirect('main/successlogin');
					 }
					 else
					 {
					  return back()->with('error', 'Wrong Login Details');
					 }




		}


}
