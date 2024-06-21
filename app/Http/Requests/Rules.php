<?php

namespace App\Http\Requests;

trait Rules
{
    private const ONLY_ALPHA = 'regex:/[a-zA-Z0-9áÁàÀéÉíÍóÓúÚâÂêÊôÔãÃõÕüÜçÇ]+$/';
    private const ONLY_NUMBER = 'regex:/[0-9]+$/';
    private const ONLY_TEXT = 'regex:/[a-zA-ZáÁàÀéÉíÍóÓúÚâÂêÊôÔãÃõÕüÜçÇ]+$/';

    private const DATE = 'date';
    private const DATE_AFTER_1900 = 'after_or_equal:1900-01-01';

    private const DATE_FORMAT_PT_BR = 'date_format:d/m/Y';
    private const DATE_FORMAT_DB = 'date_format:Y-m-d';

    private const REQUIRED = "required";

    private const MAX_1   = "max:1";
    private const MAX_2   = "max:2";
    private const MAX_3   = "max:3";
    private const MAX_4   = "max:4";
    private const MAX_5   = "max:5";
    private const MAX_6   = "max:6";
    private const MAX_7   = "max:7";
    private const MAX_8   = "max:8";
    private const MAX_9   = "max:9";

    private const MAX_10  = "max:10";
    private const MAX_11  = "max:11";
    private const MAX_12  = "max:12";
    private const MAX_13  = "max:13";
    private const MAX_14  = "max:14";
    private const MAX_15  = "max:15";
    private const MAX_16  = "max:16";
    private const MAX_17  = "max:17";
    private const MAX_18  = "max:18";
    private const MAX_19  = "max:19";

    private const MAX_20  = "max:20";
    private const MAX_30  = "max:30";
    private const MAX_40  = "max:40";
    private const MAX_50  = "max:50";
    private const MAX_60  = "max:60";
    private const MAX_70  = "max:70";
    private const MAX_80  = "max:80";
    private const MAX_90  = "max:90";
    private const MAX_100 = "max:100";
    private const MAX_200 = "max:200";

    private const MIN_1  = "min:1";
    private const MIN_2  = "min:2";
    private const MIN_3  = "min:3";
    private const MIN_4  = "min:4";
    private const MIN_5  = "min:5";
    private const MIN_6  = "min:6";
    private const MIN_7  = "min:7";
    private const MIN_8  = "min:8";
    private const MIN_9  = "min:9";
    private const MIN_10 = "min:10";
    private const MIN_11 = "min:11";
    private const MIN_12 = "min:12";
    private const MIN_13 = "min:13";
    private const MIN_14 = "min:14";

}
