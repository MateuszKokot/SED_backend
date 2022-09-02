<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;

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
        error_log('TestController',0);
//        error_log('COOKIE: ' . $request->cookie('id') ,0);
//        foreach ($info as $value) {
//            error_log( "HEADER: " . implode(" ",$value),0);
//        }


        $mytime = Carbon::now();

        if ($request->hasHeader('id')){
            $content = $mytime->toDateTimeString() . " - Ma heder";
        } else {
            $content = $mytime->toDateTimeString() . " - Nie ma hedera";
        }


        return response($content);
    }
}
