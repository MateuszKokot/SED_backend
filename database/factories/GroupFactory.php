<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Member;
use App\Models\User;
use Google\Cloud\Firestore\FirestoreClient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Pola konfiguracyjne
        $max_members = 20;



        // Generuje ID dla nowej grupy
        $last_group_id = Group::orderByDesc('id')->first()->id;
        $new_group_id = $last_group_id + 1;


        // Pobieram listę wszystkich userów
        $users = User::all();

        // Tworzę nową grupę w FIRESTORE
            //pobieram obiekt firestore
        $firestore = new FirestoreClient([
            'projectId' => 'szukam-ekipy-do',
//            'projectId' => 'szukamekipy-d023f',
        ]);
            //pobieram kolekcje
        $collection = $firestore->collection('groups');
            //dodaje nowy dokument/grupę
        $newUser = $collection->add([
            'members' => [1,3,5],
            'name' => 'TestowaMati',
            'owner' => 'email@email.com'
        ]);
            //pobieram firebase czat id utworzonej grupy
        $firebaseChatID = $newUser->id();

        // Losuje memberów
        $members[] = array_rand($users->toArray(), rand(1,$max_members) );

        // Tworzę połączenia memberów i bazy danych
        foreach ($members as $member) {
            $newMember = new Member;
            $newMember->user_id = $member;
            $newMember->group_id = $new_group_id;
            $newMember->save();
        }

        // Losuje ownera
        $owner = $users[array_rand($members, 1)];

        //TODO dopisać generator name na podstawie "Szukam" i losowego worda z BD. Są jakieś internetowe generatory
        //TODO pozycja geograficzna powinna obejmować mały obszar, żeby wyszukiwanie po lokalizacji działało
        //TODO description mogłoby być na podstawie wylosowanego worda
        //TODO event time date powinno być w wąskim zakresie dat, żeby przeszukiwanie było możliwe/ NAjlepiej daty inżynierki tj styczeń marzec 2023



        return [
            'id' => $new_group_id,
            'firebase_chat_id' => $firebaseChatID,
            'name',
            'description',
            'latitude',
            'longitude',
            'event_date',
            'event_time',
            'max_members' => $max_members,
            'owner' => $owner,
            'popularity' => 0,

        ];
    }
}
