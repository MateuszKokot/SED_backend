<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //Connect with firebase
        $auth = app('firebase.auth');

        //Retrieve itToken form request header
        $idToken = $request->header('idToken');

        //Verify id token in firebase
        try {
            $verifiedIdToken = $auth->verifyIdToken($idToken);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }

        // Retrieve the UID (User ID) from the verified Firebase credential's token
        $uid = $verifiedIdToken->claims()->get('sub');

        if ($uid != null){
            try {
                $userFirebase = $auth->getUser($uid);
                $name = $userFirebase->displayName;
                $email = $userFirebase->email;
            } catch (\Exception $e) {
                return response()->json([
                    'message' => $e->getMessage()
                ], 401);
            }
        }

        // Retrieve the user model linked with the Firebase UID
        $user = User::where('firebase_uid',$uid)->first();

        // Here you could check if the user model exist and if not create it

        $user =  User::firstOrCreate(
            ['firebase_uid' => $uid],
            ['name' => $name,
                'email' => $email,
                'popularity'=> 0 ]
        );


        // Once we got a valid user model
        // Create a Personnal Access Token
        $tokenResult = $user->createToken('Personal Access Token');

        // Store the created token
        $token = $tokenResult->token;

        // Add a expiration date to the token
        $token->expires_at = Carbon::now()->addWeeks(1);

        // Save the token to the user
        $token->save();

        // Return a JSON object containing the token datas
        // You may format this object to suit your needs
//        return response()->json([
//            'id' => $user->id,
//            'access_token' => $tokenResult->accessToken,
//            'token_type' => 'Bearer',
//            'expires_at' => Carbon::parse(
//                $tokenResult->token->expires_at
//            )->toDateTimeString()
//        ]);

//        $content = $tokenResult->accessToken;
        $startTime = Carbon::now();
        $finishTime = Carbon::parse($tokenResult->token->expires_at);
        $minutesBetwen = $finishTime->diffInMinutes($startTime);
        $cookieExpires = $minutesBetwen;
        $content = [
            'id' => $user->id,
            'Authorization' => 'Bearer ' . $tokenResult->accessToken,
            'expires_at' => $finishTime->toDateTimeString()
        ];
        return response($content);

    }
}
