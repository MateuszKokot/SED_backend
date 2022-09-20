<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\KeyWord;
use App\Models\Member;
use App\Models\Tag;
use App\Models\User;
use App\Models\Word;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "index";
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Tworzenie grupy
        $newGroup = new Group;
        $newGroup->firebase_chat_id = $request->firebase_chat_id;
        $newGroup->name = $request->name;
        $newGroup->description = $request->description;
        $newGroup->coordinates = $request->coordinates;
        $newGroup->date = $request->date;
        $newGroup->max_members = $request->max_members;
        $newGroup->save();

        // Tworzenie memberów grupy
        $members = $request->members;
        foreach ($members as $member){
            $newMembers = new Member();
            $newMembers->group_id = $newGroup->id;
            $newMembers->user_id = $member;
            $membersForContent[] = User::where('id',$member)->get()[0];
            $newMembers->save();
        }


        // Tworzenie tagów grupy
        $tags = $request->tags;
        foreach ($tags as $tag){
            $keyWord = KeyWord::where('keyword', $tag)->get();
            $keywordsForContent[] = $keyWord[0]->keyword;
            $newTags = new Tag();
            $newTags->group_id = $newGroup->id;
            $newTags->keyword_id = $keyWord[0]->id;
            $newTags->save();
        }

        $content['group'] = Group::where('id', $newGroup->id)->get();
        $content['keywords'] = $keywordsForContent;
        $content['members'] = $membersForContent;
        return response($content);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Pobieranie grupy
        $content['group'] = Group::where('id', $id)->get();

        // Pobieranie tagów grupy
        $tags = Tag::where('group_id', $id)->get();
        foreach ($tags as $tag) {
            $keyword[] = KeyWord::where('id', $tag->keyword_id)->value('keyword');
        }
        $content['keywords'] = $keyword;

        //Pobieranie memberów grupy
        $members = Member::where('group_id', $id)->get();
        foreach ($members as $member){
            $membersForContent[] = User::where('id', $member->user_id)->get()[0];
        }
        $content['members'] = $membersForContent;

        return response($content);
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
        return "update";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Member::where('group_id', $id)->delete();
        Tag::where('group_id', $id)->delete();
        $deleted = Group::where('id', $id)->delete();
        if ( $deleted == 1 ) {
            $content = "deleted";
        } else {
            $content = "undeleted or not existing";
        }
        return response($content);
    }
}
