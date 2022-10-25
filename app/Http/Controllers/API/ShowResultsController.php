<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Tag;
use Illuminate\Http\Request;

class ShowResultsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        session_start();
        $resultsByDistance = [];
        $IDsInOneArray = [];
        $IDsAfterInDateAndWithTags = [];
        //  Znalezienie id keywords-ów z głównego zapytania
        foreach ($request->keywords as $keyword) {
            $eachKeyWordFromQuery[] = $keyword;
        }

        // Znalezienie grup z prawidłową datą wydarzenia
        $minDate = $request->get('minDate');
        $maxDate = $request->get('maxDate');
        $minTime = $request->get('minTime');
        $maxTime = $request->get('maxTime');

        $groupsInDate = Group::query()->whereBetween('event_date', [$minDate, $maxDate])->whereBetween('event_time', [$minTime, $maxTime])->get();

        //Zapisanie ID grup w danej dacie do tablicy
        foreach ($groupsInDate as $group) {
            $IDsInOneArray[] = $group->id;
        }

        //Znalezienie ID grup, które mają tagi z zapytania
        $i = 0;
        foreach ($eachKeyWordFromQuery as $tagID) {
            $groupID[$i] = Tag::where("keyword_id", $tagID)->pluck('group_id');
            $i++;
        }

        //Zapisanie ID grup ze zgodnymi tagami (tylko tych które są w podanej dacie)
        // do tablicy jednowymiarowej (na potrzeby dalszych operacji nie mogła to być tablica wielowymiarowa)
        for ($j = 0; $j < $i; $j++) {
            foreach ($groupID[$j] as $group) {
                if (in_array($group, $IDsInOneArray))
                    $IDsAfterInDateAndWithTags[] = $group;
            }
        }
        if ($IDsAfterInDateAndWithTags == []) {
            return response('Brak pasujących grup, zmień kryteria, lub utwórz własną grupę.');
        }

        // $max - wyznaczenie ile razy najwięcej powatrza się jakieś ID grupy (to które powtarza się najwięcej razy)
        $max = max(array_count_values($IDsAfterInDateAndWithTags));

        // $countOfTags - wyznaczenie ile razy powtarza się każde ID grupy
        $countOfTags = (array_count_values($IDsAfterInDateAndWithTags));

        // $maxID - wyznaczenie najwyższego ID
        $maxID = max(array_keys($countOfTags));

        // Wyznaczenie, ID grup, które mają maksymalną liczbę zgodnych tagów
        for ($x = 0; $x <= $maxID; $x++) {
            if (isset($countOfTags[$x])) {
                if ($countOfTags[$x] == $max) {
                    $IDsWithMaxTags[] = $x;
                }
            }
        }
        //ODFILTROWANIE WYNIKÓW W PROMIENIU $maxDistance

        $inputLatitude = $request->latitude;
        $inputLongitude = $request->longitude;
        $maxDistance = $request->maxDistance;

        foreach ($IDsWithMaxTags as $id) {
            foreach (Group::where('id', $id)->get() as $group) {
                $lat = $group->latitude;
                $lon = $group->longitude;
                $distance = $this->getDistanceBetween($lat, $lon, $inputLatitude, $inputLongitude);
                if ($distance <= $maxDistance) {
                    $group->distance = $distance;
                    $resultsByDistance[] = $group;
                }
            }
        }
        if ($resultsByDistance == []) {
            return response('Brak pasujących grup, zmień kryteria, lub utwórz własną grupę.');
        } else return response($resultsByDistance);
    }

    public function getDistanceBetween($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        $distance = $distance * 1.609344;
        return (round($distance, 2));
    }
}
