<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;

class AuthController extends Controller
{
    function login()
    {
        return view('loginsignup');
    }

    public function loginOrRegister(Request $request)
    {
        info($request->all());

        if ($request->input('action') == 'login') {
            // Handle login logic
            //error message saying email or password is required if users dont fill it
            $request->validate([
                'email' => 'required',
                'password' => 'required'
            ]);

            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                // Retrieve the authenticated user
                $customer = Auth::user();
                session(['customer' => $customer]);

                // Pass user data to the profile view
                // return view('home', compact('customer'));

                return redirect()->intended(route('home'));

                // $homeController = App::make(HomeController::class);
                // return $homeController->home();

                // return redirect(route('home'));
            }
            return redirect(route('login'))->with("error", "Login details are not valid");
        } elseif ($request->input('action') == 'register') {
            // Handle registration logic
            //error message saying email or password is required if users dont fill it
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:customers',
                'password' => 'required'
            ]);

            $data['customerName'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = Hash::make($request->password);
            $customer = Customer::create($data);
            if (!$customer) {
                return redirect(route('loginOrRegister'))->with("error", "Registration failed, try again");
            }
            return redirect(route('login'))->with("success", "Registration success, please login");
        }

        // Common logic for both login and registration

        return redirect()->route('home'); // Redirect to the home page after login or registration
    }

    function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}
