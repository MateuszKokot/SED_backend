<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\KeyWord;
use App\Models\Tag;
use Illuminate\Http\Request;

class ShowResultsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request){
        session_start();

        //  Znalezienie id keywords-ów z głównego zapytania
        foreach ($_SESSION['keywords'] as $keyword) {
            $eachKeyWordFromQuery[]= $keyword[1];
        }

        // Znalezienie grup z prawidłową datą wydarzenia
        $minDate = $request->get('minDate');
        $maxDate = $request->get('maxDate');
        $minTime = $request->get('minTime');
        $maxTime = $request->get('maxTime');

        $groupsInDate = Group::query()->whereBetween('event_date',[$minDate,$maxDate])->whereBetween('event_time',[$minTime,$maxTime])->get();

        //ODFILTROWANIE WYNIKÓW W PROMIENIU $maxDistance

        $inputLatitude = $request->get('latitude');
        $inputLongitude = $request->get('longitude');
        $maxDistance = $request->get('maxDistance');

        foreach ($groupsInDate as $inDate) {
            foreach (Group::where('id',$inDate['id'])->get() as $group) {
                $lat = $group->latitude;
                $lon = $group->longitude;
                $distance = $this->getDistanceBetween($lat, $lon, $inputLatitude, $inputLongitude);
                if ($distance <= $maxDistance) {
                    $group->distance = $distance;
                    $resultsByDistance[] = $group;
                    $distanceToArray[$inDate['id']]=$distance;
                }
            }
        }

        //Zapisanie ID grup w danym promieniu i dacie do tablicy
        foreach ($resultsByDistance as $group) {
            $IDsInOneArray[]=$group->id;
        }

        //Znalezienie ID grup, które mają tagi z zapytania
        $i=0;
        foreach ($eachKeyWordFromQuery as $tagID){
            $groupID[$i]=Tag::where("keyword_id",$tagID)->pluck('group_id');
            $i++;
        }
        //Zapisanie ID grup ze zgodnymi tagami (tylko wych które są w podanym promieniu i dacie)
        // do tablicy jednowymiarowej (na potrzeby dalszych operacji nie mogła to być tablica wielowymiarowa)
        for($j=0;$j<$i;$j++) {
            foreach ($groupID[$j] as $group) {
                if(in_array($group,$IDsInOneArray)){
                    $IDsAfterAllFilters[]=$group;
                }
            }
        }

        // $max - wyznaczenie ile razy najwięcej powatrza się jakieś ID grupy (to które powtarza się najwięcej razy)
        $max = max(array_count_values($IDsAfterAllFilters));

        // $countOfTags - wyznaczenie ile razy powtarza się każde ID grupy
        $countOfTags = (array_count_values($IDsAfterAllFilters));

        //$maxID - wyznaczenie najwyższego ID
        $maxID=max(array_keys($countOfTags));

        // Wyznaczenie, ID grup, które mają maksymalną liczbę zgodnych tagów
        for($x=0;$x<=$maxID;$x++){
            if(isset($countOfTags[$x])) {
                if ($countOfTags[$x] == $max) {
                        $IDsWithMaxTags[]=$x;
                }
            }
        }

        foreach ($IDsWithMaxTags as $id){
            foreach (Group::where('id',$id)->get() as $group){
                $group->distance = $distanceToArray[$id];
                $correctGroups[]=$group;

            }
        }
        return response($correctGroups);

    }
    public function getDistanceBetween($latitude1, $longitude1, $latitude2, $longitude2) {
        $theta = $longitude1 - $longitude2;
        $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        $distance = $distance * 1.609344;
        return (round($distance,2));
    }
}
