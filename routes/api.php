<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('api')->group(function () {

        Route::prefix('v1')->group(function () {


            Route::prefix('mobile')->group(function () {


                Route::prefix('user')->group(function () {


                    Route::post('/create', 'Ludo\PlayersController@create')->name('player.create');
                    Route::post('/edit', 'Ludo\PlayersController@edit')->name('player.edit');
                    Route::post('/login', 'Ludo\PlayersController@login')->name('player.login');

                    Route::prefix('wallet')->group(function () {

                        Route::post('/credit', 'Ludo\WalletController@credit')->name('player.wallet.credit');
                        Route::post('/debit', 'Ludo\WalletController@debit')->name('player.wallet.debit');
                        Route::post('/status', 'Ludo\WalletController@status')->name('player.wallet.status');
                        Route::post('/info', 'Ludo\WalletController@info')->name('player.wallet.info');

                    });





                });

                Route::prefix('match')->group(function () {

                    Route::post('/create', 'Ludo\MatchController@create')->name('match.create');
                    Route::post('/join', 'Ludo\MatchController@join')->name('match.join');
                    Route::post('/start', 'Ludo\MatchController@start')->name('match.start');
                    Route::post('/move', 'Ludo\MatchController@move')->name('match.move');

                });




            });


        });

});

