<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Http\Controllers\Controller;
use Socialite;
use Exception;
use Auth;
use File;
use Session;
use Request;




class FaceBookController extends Controller
{


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleFacebookCallback()
    {
        // try {
        // $error_code = Input::get('error_code');

        $user = Socialite::driver('facebook')->stateless()->user();

        $splitName = explode(' ', $user->getName(), 2); // Restricts it to only 2 values, for names like Billy Bob Jones

        $firstname = $splitName[0];
        $lastname = !empty($splitName[1]) ? $splitName[1] : '';
        $signup = 0;
        //  $createdUser=Patient::where('google_token',$user->getId())->first();
        $createdUser = User::where('facebook_token', $user->id)->first();
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



    public function dataDeletionCallback(Request $request)
    {
        $signed_request = $request->get('signed_request');
        $data = $this->parse_signed_request($signed_request);
        $user_id = $data['patientno'];

        // here will delete the user base on the user_id from facebook
        // Auth::guard('patient')->where([
        //   ['provider' => 'facebook'],
        //   ['provider_id' => $user_id]
        // ])->forceDelete();

        // here will check if the user is deleted
        $patient = Auth::guard('patient')->where([
            ['provider' => 'facebook'],
            ['provider_id' => $user_id]
        ])->find();


        $patient->is_delete = 1;
        // return redirect('/auth/facebook')->with('feedback', 'App removed succesfully');
        return response()->json([
            'url' => 'https://imchospital.com.pk/patientfacebookdatadel/' . $user_id,
            'confirmation_code' => $user_id,
        ]);
    }

    private function parse_signed_request($signed_request)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $secret = config('service.facebook.client_secret'); // Use your app secret here

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    private function base64_url_decode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    public function dataDelRequests($patientno)
    {
        $dataDelReq = Patient::where('patientno', $patientno)->first();

        return view('front.deletereq', compact('dataDelReq'));
    }
}
