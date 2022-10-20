<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Member;
use Illuminate\Http\Request;

class AttendController extends Controller
{
    public function whereIAttend($user_id){

        // Pobieram z tabeli "Members" krotki gdzie występuje ten user_id
        $members = Member::where("user_id", $user_id)->get();

        // Pobieram grupy gdzie user występuje jako member
        $userGroups = array();
        foreach ($members as $member){
            $userGroups[] = (Group::where('id', $member->group_id)->get())[0];
        }
        return response($userGroups);
    }
}
