<?php


namespace App\Helper;
use Illuminate\Http\Request;

class Gate
{
    public static function checkUserToken(Request $r){


        $user['id']=$r->getUser();
        $user['apiToken']=decodeLimit($r->getPassword());

                $m=\App\Helper\Players::_PlayerModel();
        $foundUser=$m->where('id','=',$user['id']);

        if($foundUser->count()==0)return false;
        $foundUser=$foundUser->first()->toArray();

        config()->set('User.LoggedIn',$foundUser);
        return $foundUser['apiToken']==$user['apiToken'];

    }
}
