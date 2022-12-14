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
        $min_latitude = 52.46180594732916;
        $max_latitude = 52.358204595367255;
        $min_longitude = 16.814250956019137;
        $max_longitude = 17.007438887674738;
        $start_date = 1672531200; // Epoch timestamp / 2023 01:00:00 GMT+01:00
        $end_date = 1680220800; // Epoch timestamp / 31 marca 2023 02:00:00 GMT+02:00
        $available_keywords = $result = [
            "czytać"=> 30961,
            "fotografia"=> 50514,
            "gra"=> 57083,
            "komputerowy"=> 80852,
            "instrument"=> 66494,
            "kolorowanka"=> 79984,
            "oglądać"=> 125543,
            "serial"=> 175075,
            "film"=> 48705,
            "pisać"=> 138511,
            "list"=> 92509,
            "słuchać"=> 179623,
            "muzyk"=> 108778,
            "podcast"=> 141112,
            "audiobook"=> 9050,
            "spacerować"=> 181485,
            "śpiewać"=> 193179,
            "gotować"=> 56820,
            "hodowla"=> 62783,
            "pszczoła"=> 160443,
            "makijaż"=> 97362,
            "parzyć"=> 133660,
            "kawa"=> 75527,
            "herbata"=> 61474,
            "piec"=> 136633,
            "pielęgnacja"=> 136802,
            "skóra"=> 178339,
            "ciało"=> 25907,
            "reperować"=> 164898,
            "przeróbka"=> 156995,
            "renowacja"=> 164772,
            "robić"=> 165930,
            "piwo"=> 138776,
            "nalewka"=> 113756,
            "wina"=> 213152,
            "przetwór"=> 157607,
            "sprzątać"=> 182954,
            "uprawa"=> 205456,
            "ogród"=> 125829,
            "roślina"=> 166919,
            "domowy"=> 36430,
            "zdrowa"=> 227717,
            "odżywiać"=> 125324,
            "zielarstwo"=> 229042,
            "biegać"=> 15036,
            "gimnastyk"=> 54613,
            "artystyczny"=> 8108,
            "golf"=> 56062,
            "jazda"=> 69387,
            "konny"=> 81509,
            "kółka"=> 84249,
            "kręgiel"=> 85110,
            "Lekki"=> 90555,
            "ćwiczenia"=> 31071,
            "rozciągać"=> 167276,
            "odbijać"=> 123292,
            "piłka"=> 138038,
            "paletka"=> 131821,
            "sport"=> 182566,
            "Walek"=> 208302,
            "wodny"=> 214738,
            "zespołowy"=> 228259,
            "zimowy"=> 229400,
            "strzelać"=> 185934,
            "łuk"=> 95939,
            "sztuka"=> 191656,
            "taniec"=> 194928,
            "wędkarstwo"=> 210673,
            "carving"=> 22062,
            "ceramik"=> 22811,
            "decoupage"=> 32131,
            "dekoracja"=> 32622,
            "ciasto"=> 25981,
            "dziergać"=> 41231,
            "farbować"=> 47404,
            "malować"=> 97848,
            "tkanina"=> 197774,
            "ubrać"=> 203333,
            "filcować"=> 48585,
            "florystyka"=> 49677,
            "układać"=> 204116,
            "bukiet"=> 20526,
            "haftować"=> 59702,
            "kaligrafia"=> 72418,
            "lepić"=> 90927,
            "masa"=> 99666,
            "solny"=> 180955,
            "plastelina"=> 139093,
            "modelina"=> 105969,
            "makrama"=> 97414,
            "origami"=> 128565,
            "patchwork"=> 134123,
            "majsterkować"=> 97228,
            "modelarstwo"=> 105960,
            "tworzyć"=> 202575,
            "makieta"=> 97357,
            "własny"=> 214249,
            "kosmetyk"=> 83313,
            "świeca"=> 193717,
            "kolaż"=> 79631,
            "papierowy"=> 132685,
            "naklejka"=> 113605,
            "szkło"=> 190684,
            "notes"=> 120389,
            "witraż"=> 213857,
            "rysować"=> 170941,
            "rzeźbiarstwo"=> 171342,
            "stemplować"=> 184476,
            "szyć"=> 192136,
            "szydełkować"=> 192143,
            "tkać"=> 197769,
            "biżuteria"=> 16281,
            "wikliniarstwo"=> 212882,
            "wyplatać"=> 219845,
            "aktorstwo"=> 2447,
            "grafik"=> 57264,
            "animacja"=> 5205,
            "modelować"=> 105974,
            "blogować"=> 16651,
            "kręcić"=> 85090,
            "montaż"=> 106895,
            "miksować"=> 104084,
            "wiersz"=> 212431,
            "Książek"=> 86815,
            "programować"=> 153064,
            "postprodukcja"=> 147850,
            "zdjąć"=> 227620,
            "fotomontaż"=> 50585,
            "projektować"=> 153125,
            "dodatek"=> 35583,
            "wnętrze"=> 214589,
            "bilard"=> 15447,
            "snooker"=> 180277,
            "szach"=> 188837,
            "warcaby"=> 208771,
            "kart"=> 74683,
            "planszówka"=> 139062,
            "nauka"=> 115428,
            "język"=> 70706,
            "puzzel"=> 161269,
            "rozwiązywać"=> 169200,
            "krzyżówka"=> 86683,
            "astronomia"=> 8595,
            "chodzić"=> 24643,
            "giełda"=> 54392,
            "starocie"=> 183814,
            "pchli"=> 134648,
            "targ"=> 195195,
            "secondo"=> 174224,
            "koncert"=> 81017,
            "escape"=> 45503,
            "miejski"=> 102902,
            "podchody"=> 141128,
            "inwestować"=> 67069,
            "mecz"=> 100799,
            "sportowy"=> 182576,
            "pomoc"=> 145149,
            "smakować"=> 179803,
            "dać"=> 31221,
            "WiN"=> 213151,
            "czekolada"=> 29634,
            "zwiedzać"=> 231664,
            "odkrywać"=> 123902,
            "opuszczony"=> 128218,
            "miejsce"=> 102888,
            "rekonstrukcja"=> 164381,
            "historyczny"=> 62649,
            "biwakować"=> 16208,
            "wyjazd"=> 218681,
            "surwiwalowy"=> 187648,
            "Górski"=> 57027,
            "wędrówka"=> 210694,
            "obserwować"=> 122419,
            "dziki"=> 41625,
            "przyroda"=> 159126,
            "paintball"=> 131450,
            "poszukiwać"=> 148115,
            "skarb"=> 177408,
            "wykrywacz"=> 219005,
            "metal"=> 101876,
            "podróżować"=> 142375,
            "lot"=> 93410,
            "tunel"=> 201968,
            "aerodynamiczny"=> 1282,
            "skok"=> 178022,
            "bungee"=> 20798,
            "spadochron"=> 181524,
            "wspinaczka"=> 216526,
            "wysokogórski"=> 220604,
            "genealogia"=> 53650,
            "rzutek"=> 171454
        ];



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

        // Zapisuje realcje member (user <-> grupa) w bazie danych
        foreach ($members as $member) {
            $newMember = new Member;
            $newMember->user_id = $member;
            $newMember->group_id = $new_group_id;
            $newMember->save();
        }

        // Losuje ownera
        $owner = array_rand($members, 1);

        // Generuje opis
        $description = fake()->text($maxNbChars = 500);

        // Generuje nazwę grupy z udziałem losowej ilości keywords
        $name = array_rand($available_keywords, rand(1,6)) . fake()->sentence($nbWords = 6, $variableNbWords = true);

        // Losuję latitude i longitude
        $decimals = 14; // number of decimal places
        $div = pow(10, $decimals);

        // Syntax: mt_rand(min, max);
        $latiude = mt_rand(0.01 * $min_latitude , 0.05 * $max_latitude) / $div;
        $longitude = mt_rand(0.01 * $min_longitude, 0.05 * $max_longitude) / $div;

        //Losuję datę
        $new_date = mt_rand($start_date, $end_date);
        $event_date = date("Y-m-d H:i:s",$new_date);

        // Losuje godzinę
        // 9 do 21 zrobie to mt randem i sklejaniem
        $event_time = '15:00';



        //TODO event time date powinno być w wąskim zakresie dat, żeby przeszukiwanie było możliwe/ NAjlepiej daty inżynierki tj styczeń marzec 2023



        return [
            'id' => $new_group_id,
            'firebase_chat_id' => $firebaseChatID,
            'name' => $name,
            'description' => $description,
            'latitude' => $latiude,
            'longitude' => $longitude,
            'event_date' => $event_date,
            'event_time' => $event_time,
            'max_members' => $max_members,
            'owner' => $owner,
            'popularity' => 0,

        ];
    }
}
