<?php

namespace App\Consts;

class Consts
{
    const ROLL_ID_POLITICS = 1;
    const ROLL_ID_FACTORY = 2;
    const ROLL_ID_STORE = 3;
    const ROLL_ID_USER = 4;

    public static $ROLL_NAMES = [
        self::ROLL_ID_POLITICS => "行政",
        self::ROLL_ID_FACTORY => "工場",
        self::ROLL_ID_STORE => "小売",
        self::ROLL_ID_USER => "個人",
    ];
}
