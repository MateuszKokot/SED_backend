<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Exception\Auth\FailedToVerifyToken;


class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "działą";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auth = app('firebase.auth');
        $idToken = $request->header('idToken');

        try {
            $verifiedIdToken = $auth->verifyIdToken($idToken);
        } catch (\Exception $e) { // If the token has the wrong format

            return response()->json([
                'message' => $e->getMessage()
            ], 401);

        }

        // Retrieve the UID (User ID) from the verified Firebase credential's token
        $uid = $verifiedIdToken->claims()->get('sub');

        // Retrieve the user model linked with the Firebase UID
        $user = User::where('firebase_uid',$uid)->first();

        // Here you could check if the user model exist and if not create it
        if($user == null){
            $newUser = User::crete([
                'firebase_uid' => $uid,
                'name' => 
                'email'
                'password'
            ]);

        }

        //TODO Header requesta musi zawierać to
//        "Accept": "application/json",
//        'Authorization': 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNzhhMGNiNGVkZGJlNTcyMzBkYjIxOGY1ZWJhMzQ0M2VmYjk1MWFlMTgyYTQ2NTJmMjhkYTRhMWM0NmFhODYyZmE2NzMyN2YzMWNhYThmOTciLCJpYXQiOjE2NjE4MDA5OTYuNDc5MDQzLCJuYmYiOjE2NjE4MDA5OTYuNDc5MDQ0LCJleHAiOjE2OTMzMzY5OTYuNDc0ODYsInN1YiI6IjEiLCJzY29wZXMiOltdfQ.c0lx9hjbPjxno2_c2bgHyHRrkGYw47pKFAxsBA3kbG0Dvis_QoIbyK0Dz2wXQFxJ0QLe9m2VtyTjG95Z7wW8Gp_6AU39zbG7yZfk_ZuXOV_PrwwnTrCE4tJJLVBtJCnLzd8BGxwC4Y0UzvurVOCQStAcD-pXU02IpnGZZZk376RxuwEPdpKoDXQg5qRFlA5FA6XGE9vzPhyWXloOKllt43rHmI0rRwHlmyHHUMlSE7wrnwfQizuWCLGvJvZYBmCDClTzW7BHBUp5zk1faMjeSo7Q6j9jKhr1fK0VoIw8Pa_HhrL3emUU-MVvAaIr0tovq0NEay2LFGHqY3FFKzdC1gOsb3cU1DH7nFlZPIZlXO9ygu124OUlhNoAmz6FkZ50KH8zjS3vrRVCb8stTpNNr_0v2djYKrFsjuQ3CuTd0K3SJO5WjN1riKXF_DX4v0M7UjnOayk8IvLVLubEAgmM1x6SUAJTjy8iIuB0-lriuoooQcAY3x0kOaquCsYn-S_9tvUuqNvCdNJ-0O69qlV_fkRvoS4_DYg8LDL0uJ4sCR6Sw-rbG158skn1jl-kwvq53WwQwGhB0KFZOlLo91R3rHIJnUBOdHg1D-D-d2srZ9oJ2WI1swuItWF8-NM_Nx3re-SVSBD0R3TiPJV0fxBXo8S9e_wQcJRIADGOmBu9YSU',
        //TODO dopisać żeby przesyłać w headerze accestoken

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
        return response()->json([
            'id' => $user->id,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
