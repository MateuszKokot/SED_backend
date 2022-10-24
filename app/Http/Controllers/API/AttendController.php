<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\KeyWord;
use App\Models\Member;
use App\Models\Tag;
use App\Models\User;

class AttendController extends Controller
{
    public function whereIAttend($user_id){

        // Pobieram z tabeli "Members" krotki gdzie występuje ten user_id
        $members = Member::where("user_id", $user_id)->get();

        // Pobieram grupy gdzie user występuje jako member
        foreach ($members as $key=>$member){
            // Zmienne lokalne dla pętli
            $membersForContent = null;

            // Pobieranie grupy
            $content[$key] = (Group::where('id', $member->group_id)->get())[0];

            // Pobieranie tagów grupy
            $tags = Tag::where('group_id', $member->group_id)->get();
            foreach ($tags as $tag) {
                $keyword[] = KeyWord::where('id', $tag->keyword_id)->value('keyword');
            }
            $content[$key]['keywords'] = $keyword;

            //Pobieranie memberów grupy
            $membersAttendedInGroup = Member::where('group_id', $member->group_id)->get();
            foreach ($membersAttendedInGroup as $mbr){
                $membersForContent[] = User::where('id', $mbr->user_id)->get()[0];
            }
            $content[$key]['members'] = $membersForContent;

        }
        return response($content);
    }
}
