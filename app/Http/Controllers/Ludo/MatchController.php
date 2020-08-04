<?php

namespace App\Http\Controllers\Ludo;

use App\Helper\Match;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ludo\Match\Add;
use App\Http\Requests\Ludo\Match\Join;
use App\Http\Requests\Ludo\Match\Move;
use App\Http\Requests\Ludo\Match\Start;
use Illuminate\Http\Request;

class MatchController extends Controller
{

    public function create(Add $r){
        return Match::createNewMatch($r->all());
    }

    public function join(Join $r){
        return Match::joinMatch($r->all());
    }

    public function start(Start $r){
        return Match::startMatch($r->all());
    }

    public function move(Move $r){
        return Match::moveMatch($r->all());

    }







}
