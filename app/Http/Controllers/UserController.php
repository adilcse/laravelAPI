<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\User;
use App\Providers\GenerateToken;
use Illuminate\Database\QueryException;
use App\Http\Controllers\UserController;
class UserController extends Controller
{
    public function store(Request $request)
    {
        $email=$request->input('email');
        $uid=$request->input('uid');
        $name=$request->input('name');
        try{
            $id=User::store($email,$uid,$name);
            if($id){
                $status=200;
                $content=['id'=>$id];
                return response($content, $status);
                                            
            }
            else{
            $status= 404;
            $error=['error'=>'user not registered'];
            return response($error, $status);
        }                              
        }catch(QueryException $ex){ 
            $error=['type'=>'user not registered'];
            $status= 422;
            return response($error, $status);
          }
       
    }
    public function getById($id)
    {
        $content= User::getById($id);
        $status=200;
        return response($content, $status);
    }
    public function getByUid($uid)
    {
        $content= User::getByUid($uid);
       // $content=$id;
        $status=200;
        if($content['error'])
            $status=403;
        else{
            unset($content['error']);
        }
        return response($content, $status);
    }


}
