<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }

    public function authenticate(Request $request){
        $rules = [
            'email'=>'required|email',
            'password'=>'required'
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()){
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                return redirect()->route('account.dashboard');
            }else{
                return redirect()->route('account.login')->with('error','Either Email or Password is Incorrect.');
            }
        }
        else{
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }
    }

    public function register(){
        return view('register');
    }

    public function processRegister(Request $request){
        $rules = [
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required',
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()){
           
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'customer';
            $user->save();

            return redirect()->route('account.login')->with('success','You have Register SuccessFully');
        }
        else{
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }

}
