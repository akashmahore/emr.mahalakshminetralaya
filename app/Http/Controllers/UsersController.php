<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\Institute;
use App\Models\User;
use App\Models\VerifyCode;
use App\Models\VerificationCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

use Hash;

class UsersController extends BaseController
{

  // use AuthenticatesUsers;
  public function __construct()
  {
    /*$this->beforeFilter('csrf', array('on'=>'post'));
    $this->beforeFilter('auth', array('only'=>array('show','create','edit','update')));*/
    //5.5
    //$this->middleware('csrf', array('on'=>'post'));
    $this->middleware('auth', array('only' => array('show', 'create', 'edit', 'update', 'delete', 'dologin')));
  }
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function postSignin(request $request)
  {

    $otp_check  = \Config::get('app.otp');

    $institute = Institute::select('name')->first();

    if ($otp_check == "No") {
      if (\Auth::attempt(array('login' => $request->input('login'), 'password' => $request->input('password')))) {

        $login   = Auth::user()->group;

        $name = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $login = Auth::user()->group;
        Session::put('name', $name);
        Session::put('userRole', $login);

        if (!$institute) {
          if (Auth::user()->group != "Admin") {
            return Redirect::to('/')
              ->withInput($request->all())->with('error', 'Institute Information not setup yet! Please contact administrator.');
          } else {
            $institute = new Institute;
            $institute->name = "Mahalakshmi Netralaya";
            \Session::put('inName', $institute->name);
            return Redirect::to('/institute')->with('error', 'Please provide institute information!');
          }
        } else {
          \Session::put('inName', $institute->name);
          return Redirect::to('/dashboard')->with('success', 'You are now logged in.');
        }
      } else {
        return Redirect::to('/')
          ->withInput($request->all())->with('error', 'Your username/password combination was incorrect');
      }
    } else {

      if (\Auth::attempt(array('login' => $request->input('login'), 'password' => $request->input('password')))) {
        $user_id = Auth::id();
        $token = VerificationCode::where('user_id', $user_id)->where('ip_address', $request->ip());
        if ($token->count() == 0) {


          $token_create = VerificationCode::create(
            [

              'user_id' => $user_id,
              'status' => 1,
              'ip_address' => $request->ip()
            ]
          );

          if (preg_match("~^0\d+$~", Auth()->user()->phone)) {
            $phone = preg_replace('/0/', '91', Auth()->user()->phone, 1);
          } else {
            $phone = Auth()->user()->phone;
          }
          $message = 'Your verification code is ' . $token_create->code;
          
          // TODO: Integrate with your SMS provider (Twilio, AWS SNS, etc.)
          $this->sendSms($phone, $message);
          
          \Auth::logout();
          return Redirect::to('/verification_code?id=' . $token_create->id);
        }


        \Session::put('inName', $institute->name);
        return Redirect::to('/dashboard')->with('success', 'You are now logged in.');
      } else {

        return Redirect::to('/')
          ->withInput($request->all())->with('error', 'Your username/password combination was incorrect');
      }
    }
  }

  public function codeverify(Request $request)
  {
    $institute = Institute::select('name')->first();
    if (!$institute) {
      $institute = new Institute;
      $institute->name = "Mahalakshmi Netralaya";
    }
    $id = $request->get('id');
    return view('verificationcode', compact('institute', 'id'));
  }
  public function code_check(Request $request)
  {

    $check = VerificationCode::find($request->id);

    if ($check->code == $request->code) {

      if (Auth::loginUsingId($check->user_id)) {
        $name  = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $login = Auth::user()->group;
        \Session::put('name', $name);
        \Session::put('userRole', $login);
        return redirect('/dashboard');
      }
    }

    return Redirect::to('/verification_code?id=' . $request->id)
      ->withInput($request->all())->withErrors('Your code was incorrect');
  }

  public function verify_code(Request $request)
  {
    $error = \Session::get('error');
    $institute = Institute::select('name')->first();
    if (!$institute) {
      $institute = new Institute;
      $institute->name = "Mahalakshmi Netralaya";
    }
    return View('app.users.verify', compact('error', 'institute'));
  }
  public function sendcode($user_id, $phone)
  {

    $verified_code = hexdec(substr(uniqid(rand(), true), 5, 5));
    $verification_code = new VerifyCode;
    $verification_code->user_id = $user_id;
    $verification_code->code = $verified_code;
    $verification_code->save();

    $msg = "verification code is " . $verified_code;
    
    // TODO: Integrate with your SMS provider (Twilio, AWS SNS, etc.)
    $this->sendSms($phone, $msg);
  }

  public function sendSms($phone, $message)
  {
    // TODO: Implement actual SMS sending with your provider (Twilio, AWS SNS, etc.)
    // For now, just log
    \Log::info("SMS to {$phone}: {$message}");
  }

  public function verified(Request $request)
  {
    $verification_code = VerifyCode::first();

    if (!empty($verification_code) && $verification_code->code == $request->input('code')) {

      $user_id = $verification_code->user_id;
      VerifyCode::truncate();
      if (Auth::loginUsingId($user_id)) {

        $name = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $login = Auth::user()->group;
        \Session::put('name', $name);
        \Session::put('userRole', $login);
        $institute = Institute::select('name')->first();
        if (!$institute) {
          if (Auth::user()->group != "Admin") {
            return Redirect::to('/verify_code')
              ->withInput($request->all())->with('error', 'Institute Information not setup yet! Please contact administrator.');
          } else {
            $institute = new Institute;
            $institute->name = "Mahalakshmi Netralaya";
            \Session::put('inName', $institute->name);
            return Redirect::to('/institute')->with('error', 'Please provide institute information!');
          }
        } else {
          \Session::put('inName', $institute->name);
          return Redirect::to('/dashboard')->with('success', 'You are now logged in.');
        }
      }
    } else {
      return Redirect::to('/verify_code')
        ->withInput($request->all())->with('error', 'Code Not Match please enter Correct Code');
    }
  }

  public function getLogout()
  {
    if (request()->session()->pull('isAdmin', 0)) {
      $id = request()->session()->pull('adminID', 0);
      if (Auth::loginUsingId($id)) {
        $name  = Auth::user()->firstname . ' ' . Auth::user()->lastname;
        $login = Auth::user()->group;
        \Session::put('name', $name);
        \Session::put('userRole', $login);
        return redirect('/dashboard');
      }
      return redirect('/dashboard');
    }
    request()->session()->flush();
    \Auth::logout();
    return redirect('/')->with('message', 'Your are now logged out!');
  }
  public function dologin($id)
  {
    // Only a signed-in administrator may assume another identity. Without these
    // checks this route is an unauthenticated login-as-anyone bypass.
    if (!Auth::check() || Auth::user()->group !== 'Admin') {
      abort(403);
    }

    $user = User::find($id);
    if (!$user) {
      abort(404);
    }

    // The account to return to is the real signed-in admin, never a value
    // supplied in the URL: getLogout() passes it back to Auth::loginUsingId().
    $adminId = Auth::id();

    request()->session()->forget('isAdmin');
    request()->session()->forget('adminID');
    request()->session()->forget('surl');
    request()->session()->put('isAdmin', 1);
    request()->session()->put('adminID', $adminId);

    if (Auth::loginUsingId($id)) {

      $name  = Auth::user()->firstname . ' ' . Auth::user()->lastname;
      $login = Auth::user()->group;
      \Session::put('name', $name);
      \Session::put('userRole', $login);
      return redirect('/dashboard');
    }
  }

  public function show()
  {
    $users = User::all();
    $user = array();
    return View('app.users', compact('users', 'user'));
  }
  public function create(Request $request)
  {
    $rules = [
      'firstname' => 'required',
      'lastname' => 'required',
      'email' => 'required|email',
      'group' => 'required',
      'desc' => 'required',
      'login' => 'required',
      'password' => 'required'

    ];
    $validator = \Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return Redirect::to('/users')->withInput($request->all())->withErrors($validator);
    } else {

      $uexits = User::select('*')->where('email', '=', $request->input('email'))->where('login', '=', $request->input('login'))->get();
      if (count($uexits) > 0) {
        $errorMessages = new \Illuminate\Support\MessageBag;
        $errorMessages->add('deplicate', 'User all ready exists with this email or login');
        return Redirect::to('/users')->withInput($request->all())->withErrors($errorMessages);
      } {
        $user = new User;
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->login = $request->input('login');
        $user->desc = $request->input('desc');
        $user->email = $request->input('email');
        $user->group = $request->input('group');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return Redirect::to('/users')->with("success", "User Created Succesfully.");
      }
    }
  }
  public function edit($id)
  {
    $user = User::find($id);
    $users = User::all();
    return View('app.users', compact('users', 'user'));
  }
  public  function update(Request $request)
  {
    $rules = [
      'firstname' => 'required',
      'lastname'  => 'required',
      'email'     => 'required|email',
      'group'     => 'required',
      'desc'      => 'required',
      'login'     => 'required',
      'password'  => 'required'

    ];
    $validator = \Validator::make($request->all(), $rules);
    if ($validator->fails()) {
      return Redirect::to('/usersedit/' . $request->input('id'))->withErrors($validator);
    } else {

      $uexits = User::select('*')->orwhere('email', '=', $request->input('email'))->first();
      if ($uexits && $uexits->count() > 0) {

        if ($uexits->id != $request->input('id')) {
          $errorMessages = new \Illuminate\Support\MessageBag;
          $errorMessages->add('deplicate', 'User all ready exists with this email');
          return Redirect::to('/users')->withInput($request->all())->withErrors($errorMessages);
        } else {
          $user            = User::find($request->input('id'));
          $user->firstname = $request->input('firstname');
          $user->lastname  = $request->input('lastname');
          $user->login     = $request->input('login');
          $user->desc      = $request->input('desc');
          $user->email     = $request->input('email');
          $user->group     = $request->input('group');
          $user->password  = Hash::make($request->input('password'));
          $user->save();
          return Redirect::to('/users')->with("success", "User Updated Succesfully.");
        }
      } else {
        $user = User::find($request->input('id'));
        $user->firstname = $request->input('firstname');
        $user->lastname = $request->input('lastname');
        $user->login = $request->input('login');
        $user->desc = $request->input('desc');
        $user->email = $request->input('email');
        $user->group = $request->input('group');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return Redirect::to('/users')->with("success", "User Updated Succesfully.");
      }
    }
  }

  public function delete($id)
  {
    $user = User::find($id);
    $user->delete();
    return Redirect::to('/users')->with("success", "User Deleted Succesfully.");
  }

  public function generateCode($codeLength = 4)
  {
    $min = pow(10, $codeLength);
    $max = $min * 10 - 1;
    $code = mt_rand($min, $max);

    return $code;
  }

  public function session(Request $request)
  {
    return Redirect::to('/')->with('message', 'This is a success message222');
  }
}
