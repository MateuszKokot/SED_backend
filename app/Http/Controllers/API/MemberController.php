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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = $request->user_id;
        $group_id = $request->group_id;
        if(Group::where('id',$group_id)->count()==0){
            return response("Podana grupa nie istnieje");
        }
        if(User::where('id',$user_id)->count()==0){
            return response("Podany użytkownik nie istnieje");
        }
        $count=Member::where('user_id',$user_id)->where('group_id',$group_id)->count();
        if($count==0){
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user_id=$request->user_id;
        $group_id=$request->group_id;
        if(Member::where('user_id',$user_id)->where('group_id',$group_id)->count()==0){
            return response("Dany rekord nie istnieje!");
        }else{
            Member::where("user_id",$user_id)->where("group_id",$group_id)->delete();
            return response("Rekord został usunięty prawidłowo!");
        }


    }
}
