<?php


namespace App\Helper;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Players
{

    private static $Columns=[ 'username','password','email','mobile','id','apiToken' ];
    private static $ColumnsHashed=[ 'password'];
    private static $ColumnsPublic=[ 'id','username','email','username','mobile','apiToken'];


    public static function createNewPlayer($data){



        $m=self::getPlayerModel();
        $last=$m->latest('id')->first();

        $UserId= ( $last!=null && $last->count()>0 )? $last->toArray()['id']+1:1;
        $data['id']=$UserId;

        $player=[];
        foreach (self::$Columns as $c){
            if(array_key_exists($c,$data))$player[$c]=(in_array($c,self::$ColumnsHashed))?Hash::make($data[$c]):$data[$c];
        }
        $player['created_at']=now();
        foreach (self::$ColumnsHashed as $c){
            unset($data[$c]);
        }


        return ($m->insert($player) && \App\Helper\Wallet::createWalletForUser($data['id']) )?throwData(['Player Created successfully.'],$data):throwError(['Error: Player not created.']);




    }

    public static function editPlayer($data){

        $m=self::getPlayerModel();
        $player=[];
        foreach (self::$Columns as $c){
            if(array_key_exists($c,$data))$player[$c]=(in_array($c,self::$ColumnsHashed))?Hash::make($data[$c]):$data[$c];
        }

        return ($m->where('id',$player['id'])->update($player))?throwData(['Player Updated successfully.']):throwError(['Error: Player not updated.']);

    }

    public static function loginPlayer($data){

        $m=self::getPlayerModel();
        $user=$m->where('username',$data['username'])->first()->toArray();
        $loginStatus=Hash::check($data['password'], $user['password']);

        $apiToken=Str::uuid();

        while($m->where('apiToken',$apiToken)->count()>0){
            $apiToken=Str::uuid();
        }
        if($loginStatus)self::editPlayer(['id'=>$user['id'],'apiToken'=>$apiToken]);
        $outUser=[];
        foreach (self::$ColumnsPublic as $c){

            if(array_key_exists($c,$user))$outUser[$c]=$user[$c];

        }
        $outUser['apiToken']=$apiToken;
        return ($loginStatus)?throwData(['Player Logged in successfully.'],$outUser):throwError(['Error: Player credentials not matched.']);

    }


    private static function getPlayerModel(){
        $m=new \App\Model\Ludo\Players();
        return$m;
    }

    public static function _PlayerModel(){
        return self::getPlayerModel();
    }






}
