<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('admin/login');
    }

    //This method will authenticate admin user
    public function authenticate(Request $request){
        $rules = [
            'email'=>'required|email',
            'password'=>'required'
        ];
        $validator = Validator::make($request->all(),$rules);

        if($validator->passes()){
            if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])){

                if(Auth::guard('admin')->user()->role != 'admin'){
                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error','You are not authorize to Access this Page');
                }
                return redirect()->route('admin.dashboard');
            }else{
                return redirect()->route('admin.login')->with('error','Either Email or Password is Incorrect.');
            }
        }
        else{
            return redirect()->route('admin.login')->withInput()->withErrors($validator);
        }
    }

    //This method will logout admin user
    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
