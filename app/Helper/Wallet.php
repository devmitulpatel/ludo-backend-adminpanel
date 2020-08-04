<?php


namespace App\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Wallet
{



    public static function createWalletForUser($userId){
        return self::createWalletTableForUser($userId);
    }


    private static function createWalletTableForUser($userId){
        $table=implode('_',['wallet',$userId]);
        Schema::create($table, function (Blueprint $table) {
            $table->id();
            $table->string('type')->default(0);
            $table->integer('amount')->default(0);
            $table->integer('currentBalance')->default(0);
            $table->longText('refCode')->default('');
            $table->longText('refData')->default('[]');
            $table->timestamps();
        });

        $m=self::getWalletUserModel($userId);
        $m->insert(['type'=>1]);
        return true;
    }
    private static function getWalletUserModel($userId){
        $table=implode('_',['wallet',$userId]);
        return DB::table($table);

    }

    public static function Entry($userId,$type=1,$amount=0,$data=[]){

        $UserModel=\App\Helper\Players::_PlayerModel();
        $UserModel=$UserModel->where('id',$userId);
        $WalletModel=self::getWalletUserModel($userId);
        $last=$WalletModel->latest('id');
        //dd($last);
        $last=(array)$last->first();

        $lastBalance= ( $last!=null )?$last ['currentBalance']:0;
        if(!$type && $lastBalance < $amount)return throwError(['Current Balance is lower than Amount']);
        $newEntry=[
            'type'=>$type,
            'amount'=>$amount,
            'currentBalance'=>($type)?$amount+$lastBalance:$lastBalance-$amount
        ];
        if(count($data)>0 && array_key_exists('refData',$data))$newEntry['refData']=$data['refData'];
        if(count($data)>0 && array_key_exists('refCode',$data))$newEntry['refCode']=$data['refCode'];

        //$WalletModel0>insert()
        $newEntry['created_at']=now();
        $newEntry['updated_at']=now();



        return ($WalletModel->insert($newEntry) && $UserModel->update(['currentBalance'=>$newEntry['currentBalance']]) )? throwData(['Balance updated']):throwError(['Balance Not updated']);


    }


}
