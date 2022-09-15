<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\KeyWord;
use App\Models\Word;
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
        // Initializing variables

        // Get string form input
        $inputString = $request->get('inputString');
        // removes all characters except these [^a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ]
        $sterilizedInputString = preg_replace("/[^a-zA-ZżźćńółęąśŻŹĆĄŚĘŁÓŃ\s]+/",
            "", $inputString);
        // Split input string into words. Separator is " ".
        $delimiter = ' ';
        $words = explode($delimiter, $sterilizedInputString);
        // Match word with keyword and add it to associative Array
        if($sterilizedInputString != null) {
            foreach ($words as $word){
                // Jump to next iteration if word don't exist.
                if ($word == "") {continue;}
                // Get row from WORDS table
                $wordRowFromDB = Word::where('word', $word)->first();
                // Make pair word and keyWord if variable isn't empty.
                if ($wordRowFromDB == null){
                    $pairsOfWordAndKeywords[$word] = null;
                } else {
                    // Add a pair of words to array
                    $pairsOfWordAndKeywords[$word] = KeyWord::where('id', $wordRowFromDB['keyword_id'])->value('keyword');
                    // Increment popularity for keyword and word in DB
                    $popularityOfKeyword = 1 + KeyWord::where('id', $wordRowFromDB['keyword_id'])->value('popularity');
                    $popularityOfWord = 1 + intval($wordRowFromDB['popularity']);
                    KeyWord::where('id', $wordRowFromDB['keyword_id'])
                        ->update(['popularity' => $popularityOfKeyword]);
                    Word::where('id', $wordRowFromDB['id'])
                        ->update(['popularity' => $popularityOfWord]);
                }
            }
        } else {
            $pairsOfWordAndKeywords['empty'] = 'empty';
        }
        return response($pairsOfWordAndKeywords);
    }
}
