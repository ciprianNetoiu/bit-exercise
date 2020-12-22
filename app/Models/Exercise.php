<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model {
    use HasFactory;

    const COLOR_BLUE=1;
    const COLOR_RED=2;
    const COLOR_GREEN=3;
    const COLOR_YELLOW=4;
    const COLOR_ORANGE=5;
    const COLOR_BROWN=6;
    const COLOR_PINK=7;
    const COLOR_VIOLET=8;
    const COLOR_GRAY=9;
    const COLOR_BLACK=10;

    public static function getListOfColors(): array {
        return [
            ['id'=>self::COLOR_BLUE,'name'=>'Blue'],
            ['id'=>self::COLOR_RED,'name'=>'Red'],
            ['id'=>self::COLOR_GREEN,'name'=>'Green'],
            ['id'=>self::COLOR_YELLOW,'name'=>'Yellow'],
            ['id'=>self::COLOR_ORANGE,'name'=>'Orange'],
            ['id'=>self::COLOR_BROWN,'name'=>'Brown'],
            ['id'=>self::COLOR_PINK,'name'=>'Pink'],
            ['id'=>self::COLOR_VIOLET,'name'=>'Violet'],
            ['id'=>self::COLOR_GRAY,'name'=>'Gray'],
            ['id'=>self::COLOR_BLACK,'name'=>'Black'],
        ];
    }

}
