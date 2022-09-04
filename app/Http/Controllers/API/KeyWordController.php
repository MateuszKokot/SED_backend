<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KeyWord;
use Illuminate\Http\Request;

class KeyWordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // Get string form input
        $inputString = $request->get('inputString');
        // removes all characters except these [^a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ]
        $sterilizedInputString = preg_replace("/[^a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ\s]+/",
            "", $inputString);
        // Split input string into words. Separator is " ".
        $delimiter = ' ';
        $words = explode($delimiter, $sterilizedInputString);
        // Match word with keyword and add it to associative Array
        foreach ($words as $word){
            if ($word == "") {continue;}
            $keyWord = KeyWord::where('word', $word)->first('keyword');
            if ($keyWord == null){
                $pairsOfWordAndKeywords[$word] = null;
            } else {
                $pairsOfWordAndKeywords[$word] = $keyWord['keyword'];
            }
        }
        return $pairsOfWordAndKeywords;
    }
}
