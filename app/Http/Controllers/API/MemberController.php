<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index() // GET
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $user_email = $request->email;
        $firebase_id = $request->firebase_chat_id;
        if(Group::where('firebase_chat_id',$firebase_id)->count()==0){
            return response("Podana grupa nie istnieje");
        }
        if(User::where('email',$user_email)->count()==0){
            return response("Podany użytkownik nie istnieje");
        }
        $group_id= Group::where('firebase_chat_id',$firebase_id)->value('id');
        $user_id = User::where('email', $user_email)->value('id');;
        if((Member::where('user_id',$user_id)->where('group_id',$group_id)->count())==0){
            $newMember = new Member;
            $newMember->user_id = $user_id;
            $newMember->group_id = $group_id;
            $newMember->save();
            return response($newMember);
        }else{
            return response('Dany rekord już istnieje');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id) //GET/{ID}
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id) //PATCH/{id}
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user_email = $request->email;
        $firebase_id = $request->firebase_chat_id;
        $group_id= Group::where('firebase_chat_id',$firebase_id)->value('id');
        $user_id = User::where('email', $user_email)->value('id');;
        if(Member::where('user_id',$user_id)->where('group_id',$group_id)->count()==0){
            return response("Dany rekord nie istnieje!");
        }else{
            Member::where("user_id",$user_id)->where("group_id",$group_id)->delete();
            return response("Rekord został usunięty prawidłowo!");
        }


    }
}
