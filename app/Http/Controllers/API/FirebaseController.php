<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

class FirebaseController extends Controller
{
    public function userByUID ($uid){
        $auth = app('firebase.auth');
        return $auth->getUser($uid);
    }
}
