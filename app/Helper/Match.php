<?php


namespace App\Helper;


use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Match
{


    private static $Columns=[ 'matchCode','host','otherPlayers','matchMoves','matchStarted','matchOngoing','matchEnded','created_at','updated_at' ];
    private static $ColumnsHashed=[ ];
    private static $minPlayerCount=1;
    private static $ColumnsPublic=[ 'matchCode'];
    private static $ColumnsJson=[ 'otherPlayers','matchMoves'];
    private static $defaultColumns=[
        'otherPlayers'=>"[]",
        'matchMoves'=>"[]",
        'matchStarted'=>false,
        'matchOngoing'=>false,
        'matchEnded'=>false,


    ];

    public static function createNewMatch($data){

        $m=self::getMatchModel();

        if($m->where('host',$data['id'])->count()>0)return throwError(['Player already Hosted max. Matches']);

        $match=[];
        $matchCode=strtolower(Str::random(6));
        while($m->where('matchCode',$matchCode)->count()>0){
            $matchCode=strtolower(Str::random(6));
        }
        $data['matchCode']=$matchCode;
        $data['host']=$data['id'];
        if(array_key_exists('id',$data))unset($data['id']);
        $data['created_at']=now();
        $data['updated_at']=now();
        foreach (self::$Columns as $c){
           $match[$c]=(array_key_exists($c,$data))?$data[$c]:self::$defaultColumns[$c];
        }



        $outMatch=[];
        foreach (self::$ColumnsPublic as $c){
            if(array_key_exists($c,$data))$outMatch[$c]=(in_array($c,self::$ColumnsHashed))?Hash::make($data[$c]):$data[$c];
        }

        return ($m->insert($match))?throwData(['Match Created successfully.'],$outMatch):throwError(['Error: Match not created.']);

    }

    private static function checkPlayerAlreadyIn($id,$allUser){
        $c=collect($allUser);
        $count=$c->where('id',$id)->count();

        return ($count>0)?true:false;
    }

    private static function isHost($id,$matchData){

        return ($matchData['host']==$id)?true:false;
    }

    public static function joinMatch($data){
        $userId=$data['id'];
        $matchId=$data['matchCode'];

        $matchModel=self::getMatchModel();
        $matchQuery=$matchModel->where('matchCode',$matchId)->where('matchStarted',0)->where('matchOngoing',0)->where('matchEnded',0);
        if($matchQuery->count()<1)return throwError(['Sorry Started or Ongoing match can not joined.']);

        $foundMatch=$matchQuery->first()->toArray();
        if(self::isHost($userId,$foundMatch))return throwError(['Host can not join Match created by own.']);

        $foundMatch=toArray(self::$ColumnsJson,$foundMatch);

        if(self::checkPlayerAlreadyIn($userId,$foundMatch['otherPlayers']))return throwError(['Match is already joined.']);


        $foundMatch['otherPlayers'][]= ['id'=>$userId,'jointime'=>now()->gettimestamp()];
        $foundMatch['matchMoves'][]= [
            'type'=>'join',
            'takenby'=>$userId,
            'time'=>now()->gettimestamp()
        ];

        $foundMatch=toJson(self::$ColumnsJson,$foundMatch);
        unset($foundMatch['created_at']);
        $foundMatch['updated_at']=now();
        $outMatch=[];
       // dd($foundMatch);
        return ($matchQuery->update($foundMatch))?throwData(['Match Joined successfully.'],$outMatch):throwError(['Error: Match not joined.']);


    }

    public static function startMatch($data){
        $userId=$data['id'];
        $matchId=$data['matchCode'];

        $matchModel=self::getMatchModel();
        $matchQuery=$matchModel->where('matchCode',$matchId)->where('matchStarted',0)->where('matchOngoing',0)->where('matchEnded',0);
        if($matchQuery->count()<1)return throwError(['Sorry Started or Ongoing match can not joined.']);
        $foundMatch=$matchQuery->first()->toArray();
        if(!self::isHost($userId,$foundMatch))return throwError(['Only Host of the game can start Match']);
        $foundMatch=toArray(self::$ColumnsJson,$foundMatch);
        if(count($foundMatch['otherPlayers'])<self::$minPlayerCount)return throwError(['Min. '.self::$minPlayerCount.' Player required to start match']);


        $foundMatch['matchStarted']=1;
        $outMatch=[];
        unset($foundMatch['created_at']);
        $foundMatch['updated_at']=now();
        $foundMatch=toJson(self::$ColumnsJson,$foundMatch);
        return ($matchQuery->update($foundMatch))?throwData(['Match Started successfully.'],$outMatch):throwError(['Error: Match not started.']);




    }

    public static function moveMatch($data){
        $userId=$data['id'];
        $matchId=$data['matchCode'];
        $matchModel=self::getMatchModel();
        $matchQuery=$matchModel->where('matchCode',$matchId)->where('matchStarted',1)->where('matchOngoing',0)->where('matchEnded',0);
        if($matchQuery->count()<1)return throwError(['Sorry Match is finished or Yet not started.']);

        $foundMatch=$matchQuery->first()->toArray();

        $foundMatch=toArray(self::$ColumnsJson,$foundMatch);

        if(!self::checkPlayerAlreadyIn($userId,$foundMatch['otherPlayers']) && !self::isHost($userId,$foundMatch))return throwError(['Player not found in this match.']);

        $foundMatch['matchMoves'][]=[
              "type" => "move",
              "takenby" => $userId,
              "time" => now()->gettimestamp(),
              'data'=>$data['move']
        ];

        $foundMatch=toJson(self::$ColumnsJson,$foundMatch);
        unset($foundMatch['created_at']);
        $foundMatch['updated_at']=now();
        $outMatch=[];
        return ($matchQuery->update($foundMatch))?throwData(['Move has taken successfully.'],$outMatch):throwError(['Error: Move not taken.']);



    }

    private static function getMatchModel(){
        $m=new \App\Model\Ludo\Match();
        return$m;
    }


}
