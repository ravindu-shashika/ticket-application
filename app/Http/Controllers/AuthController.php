<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
   
      public function login(Request $request){
      if($request->isMethod('post')){
        
         $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        // return $request->all();

        if (Auth::attempt($request->only('email', 'password'))) {

                $request->session()->regenerate();
            
               return redirect()->intended(route('agent.dashboard'))
                ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
          }else{
                    //echo "failed"; die;
                    return "error";
                    return redirect('/')->with('flash_message_error','Invalid Username or Password');
          }
      }
      return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }
}
