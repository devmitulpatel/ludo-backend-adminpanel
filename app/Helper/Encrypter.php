<?php


namespace App\Helper;

use Illuminate\Support\Facades\Crypt;

define('DEFAULT_ENCODE_DECODE_EXP_TIME',30);
class Encrypter
{

    public static function encode($str){
       $str= Crypt::encryptString($str);
        return $str;
    }


    public static function decode($str){
        try {
            $str= Crypt::decryptString($str);
        }catch (\Exception $e){
            return false;
        }

        return $str;
    }

    public static function encodeLimit($str,$min=DEFAULT_ENCODE_DECODE_EXP_TIME,$div="_$%"){
        $encodeStr1=self::encode($str);

        $time=now()->addMinutes($min);
        $timeZone=(string)$time->timezone;
        $finalString=implode($div,[$timeZone,$encodeStr1,(string)$time]);
//        var_dump(strlen($finalString));
        $finalString=self::encode($finalString);
  //      var_dump(strlen($finalString));
    //    dd($finalString);
        return $finalString;

    }


    public static function decodeLimit($str,$min=DEFAULT_ENCODE_DECODE_EXP_TIME,$div="_$%"){

        try {
            $decodeStr1=self::decode($str);
        }catch (\Exception $e){
            return false;
        }

        $decodeStr1Explode=explode($div,$decodeStr1);
        if(count($decodeStr1Explode)!=3)return false;
        $timeZone=$decodeStr1Explode[0];
        $str=$decodeStr1Explode[1];
        $timeString=$decodeStr1Explode[2];
        $timeGiven=now($timeZone)->setTimeFromTimeString($timeString);
        $timeNow=now($timeZone);



        if(($timeNow->diffInMinutes($timeGiven) > $min))return false;

        $str=self::decode($str);


        return $str;



    }


}
