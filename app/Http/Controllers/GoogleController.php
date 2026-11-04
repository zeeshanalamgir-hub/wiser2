<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Http\Controllers\Controller;
use Socialite;
use Exception;
use Auth;
use File;
use Session;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {

        return Socialite::driver('google')->redirect();
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGoogleCallback()
    {
        //
        // try {
        //$user = Socialite::driver('facebook')->stateless()->user();


        $user = Socialite::driver('google')->stateless()->user();

        $splitName = explode(' ', $user->getName(), 2); // Restricts it to only 2 values, for names like Billy Bob Jones

        $firstname = $splitName[0];
        $lastname = !empty($splitName[1]) ? $splitName[1] : '';
        $signup = 0;
        //  $createdUser=Patient::where('google_token',$user->getId())->first();
        $createdUser = User::where('email', $user->getEmail())->first();
        if (!$createdUser) {



            $signup = 1;


            $createdUser = new User;
            $fileContents = file_get_contents($user->getAvatar());
            File::put(public_path() . '/storage/assets/images/users/' . $user->getId() . ".jpg", $fileContents);

            $picture = $user->getId() . ".jpg";


            $createdUser->image = $picture;
            $createdUser->firstname = $firstname;
            $createdUser->lastname = $lastname;
            $createdUser->ev = 1;
            $createdUser->sv = 1;
            $createdUser->email = $user->getEmail();;
            $createdUser->username = $user->getEmail();
            $createdUser->google_token = $user->getId();
            $createdUser->save();
        }
        if ($createdUser->image == null) {
            $fileContents = file_get_contents($user->getAvatar());
            File::put(public_path() . '/storage/assets/images/users/' . $user->getId() . ".jpg", $fileContents);

            $picture = $user->getId() . ".jpg";
            $createdUser->image = $picture;
            $createdUser->save();
        }

        Auth::loginUsingId($createdUser->id);

        if ($signup == 1) {
            return redirect()->route('user.home')->with('feedback', 'Please update your profile to better services');
        }

        return redirect()->route('user.home');
    }
}
