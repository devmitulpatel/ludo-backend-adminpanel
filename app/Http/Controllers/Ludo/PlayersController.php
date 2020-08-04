<?php

namespace App\Http\Controllers\Ludo;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ludo\Users\Add;
use App\Http\Requests\Ludo\Users\Edit;
use App\Http\Requests\Ludo\Users\Login;
use App\Model\Ludo\Players;
use Illuminate\Http\Request;

class PlayersController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Add $r)
    {

        return \App\Helper\Players::createNewPlayer($r->all());


    }


    public function edit(Edit $r)
    {


        return \App\Helper\Players::editPlayer($r->all());
    }


    public function login(Login $r){
        return \App\Helper\Players::loginPlayer($r->all());

    }
}
