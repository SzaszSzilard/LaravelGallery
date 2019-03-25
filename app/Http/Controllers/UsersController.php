<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class UsersController extends Controller
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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255'],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */

    // public function add_user(array $data)
    public function add_user(Request $request)
    {
       User::create([
           'username' => $request->username,
           'firstname' => $request->firstname,
           'lastname' => $request->lastname,
           'telephone' => $request->telephone,
           'email' => $request->email,
           'password' => Hash::make($request->password),
       ]);

       return redirect('/getusers')->with('success','User added.');
    }

    public function create()
    {
      return view('createuser');
    }


    public function getthis($id)
    {
      $user = User::where("id",$id)->first();

      return $user;
    }

    public function update_pressed(Request $request) {
      $user = User::find($request->id);

      $user['username'] = $request->username;
      $user['firstname'] = $request->firstname;
      $user['lastname'] = $request->lastname;
      $user['telephone'] = $request->telephone;
      $user['email'] = $request->email;
      $user['telephone'] = $request->password;

      $user->save();

      return redirect('/getusers')->with('success','User edited.');
    }

    public function update($id)
    {

      $user = $this->getthis($id);

      return view('edituser',compact('user'));
    }

    public function delete($id)
    {
        // echo "deleted";

        // User::find($id)->delete();
        // return back()->with('success','User deleted.');

        User::find($id)->delete();
        return redirect('/getusers')->with('success','User deleted.');
    }

    public function get()
    {
        $users =  User::paginate(5);
        return view('users',compact('users'));
    }
}
