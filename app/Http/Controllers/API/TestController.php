<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Word;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use function MongoDB\BSON\toJSON;

class TestController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $auth = app('firebase.auth');
//
//        $userProperties = [
//            'email' => 'testZakladaniaUsera@zPoziomuBackendu.com',
//            'emailVerified' => false,
//            'phoneNumber' => '+15555550100',
//            'password' => 'secretPassword',
//            'displayName' => 'John Doe',
//            'photoUrl' => 'http://www.example.com/12345678/photo.png',
//            'disabled' => false,
//        ];
//
//        $createdUser = $auth->createUser($userProperties);

        $users = $auth->getUserByEmail('testZakladaniaUsera@zPoziomuBackendu.com');

        $content = $users->uid;
        return response($users);
    }
}
