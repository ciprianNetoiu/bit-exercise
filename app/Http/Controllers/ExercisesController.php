<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExercisesController extends Controller {

    public function index() {
        return view('exercises.index',['listOfColors'=>json_encode(Exercise::getListOfColors())]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function create(Request $request): string {
        $colorsCount=$request->colorsCount;
        $colors=$request->colors;
        $result=self::processColors($colorsCount,$colors);
        $message='';
        if($result) {
            $colorsList=Exercise::getListOfColors();
            $colorsListProcess =  array_column($colorsList, NULL, 'id');
// return json_encode($colorsListProcess);
            foreach($result as $key=>$variant) {

                $message.='<div>Variant '.($key+1).'</div><div class="variant-box">';
                $k=1;
                foreach($variant as $groupNr => $group) {
                    $message.='<div class="group-box"><div><strong>Group '.$k++.'</strong></div>';
                    foreach($group as $color) {
                        $message.='<div>'.$colorsListProcess[$color['colorId']]['name'].' => '.$color['value'].'</div>';
                    }
                    $message .='</div>';
                }
                $message .='</div>';
            }
        } else {
            $message = 'nothing to show';
        }

        return $message;
    }

    public static function processColors($colorsCount,$colors) {
        $marbles=$colorsCount * $colorsCount;

        $colorTotals=[];
        foreach($colors as $value) {
            $colorTotals[$value['colorId']]=['maxValue'=>$value['value'],'currentValue'=>0];
            $invalid=!is_int($value['value']);
        }
        $totalMarbles=array_sum(array_map(function($item) { return $item['value']; },$colors));
        $invalid=(!$invalid && ($colorsCount<=0 || $colorsCount>10 || $totalMarbles!=$marbles));

        if($invalid) {
            return FALSE;
        }
        // find possible values foreach color
        $colorsPossibilities=[];
        $groups=[];
        for($i=0; $i<$colorsCount; $i++) {
            for($k=0; $k<=$colors[$i]['value']; $k++) {
                if(($colors[$i]['value'] - $k>0) && ($colors[$i]['value'] - $k<=$colorsCount)) {
                    $colorsPossibilities[$i][$colors[$i]['colorId'].'-'.($colors[$i]['value'] - $k)]=[
                        'colorId'=>$colors[$i]['colorId'],
                        'value'=>$colors[$i]['value'] - $k,
                    ];
                }
            }
        }

        // get all groups combinations
        foreach($colorsPossibilities as $key=>$colorPossibilities) {
            foreach($colorPossibilities as $idValue=>$possibility) {
                $groups[]=[['colorId'=>$possibility['colorId'],'value'=>$possibility['value']]];
                foreach($colorsPossibilities as $secondPass=>$nextColor) {
                    if($secondPass>$key) {
                        foreach($nextColor as $item=>$value) {
                            if($possibility['value'] + $value['value']<=$colorsCount) {
                                $groups[]=[
                                    ['colorId'=>$possibility['colorId'],'value'=>$possibility['value']],
                                    ['colorId'=>$value['colorId'],'value'=>$value['value']],
                                ];
                            }

                        }

                    }
                }
            }
            unset($colorPossibilities[$key]);
        }

        // find correct variants
        $theLight=[];
        $countGroups=count($groups);
        for($i=0; $i<$countGroups; $i++) {
            $excludeGroups=[];
            for($u=0; $u<$countGroups; $u++) {
                $bigGroup=[];
                $groupColorTotals=$colorTotals;
                $bigGroup[$i]=$groups[$i];
                foreach($groups[$i] as $groupColor) {
                    $groupColorTotals[$groupColor['colorId']]['currentValue']+=$groupColor['value'];
                }

                for($k=0; $k<$countGroups; $k++) {
                    if($k!=$i && !in_array($k,$excludeGroups)) {
                        $canAddToGroup=TRUE;
                        $groupTotals=[];
                        for($t=0; $t<count($groups[$k]); $t++) {
                            $processGroup=$groups[$k][$t];

                            $currentValue=$processGroup['value'] + $groupColorTotals[$processGroup['colorId']]['currentValue'];
                            $maxValue=$groupColorTotals[$processGroup['colorId']]['maxValue'];

                            if($currentValue<=$maxValue) {
                                $groupTotals[$processGroup['colorId']]=$currentValue;
                            } else {
                                $canAddToGroup=FALSE;
                            }
                        }
                        if($canAddToGroup) {
                            foreach($groupTotals as $colorId=>$colorTotal) {
                                $groupColorTotals[$colorId]['currentValue']=$colorTotal;
                            }
                            if(count($bigGroup)==1) {
                                $excludeGroups[]=$k;
                            }

                            $bigGroup[$k]=$groups[$k];

                        }
                        if(count($bigGroup)==$colorsCount) {
                            break;
                        }
                    }

                }
                if(count($bigGroup)==$colorsCount) {
                    $validGroup=TRUE;
                    foreach($groupColorTotals as $totals) {
                        if($totals['maxValue']!=$totals['currentValue']) {
                            $validGroup=FALSE;
                        }
                    }
                    ksort($bigGroup);
                    if($validGroup && !in_array($bigGroup,$theLight)) {
                        $theLight[]=$bigGroup;
                    }
                }
            }
        }
        return count($theLight)>0 ? $theLight : NULL;
    }

}
