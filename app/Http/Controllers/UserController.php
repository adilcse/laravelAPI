<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Database\QueryException;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function store(Request $request)
    {
        $reqData=(array)json_decode($request->input('json'));
    
        try{
            $id=User::store($reqData);
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
            //var_dump($ex);
            return response($ex, $status);
          }
       
    }
    public function getByUid($id)
    {
            $userObj = Auth::user();
            $user=['name'=>$userObj->name,
                    'email'=>$userObj->email,
                    'user_type'=>$userObj->user_type,
                    'uid'=>$userObj->uid,
                    'address_id'=>$userObj->address_id,
                    'id'=>$userObj->id
                 ];
                 $content=[];
                 $status=200;
                 
            try{
            $address = AddressController::getUserAddress($user);
            $userCart = CartController::getUserCart($user['id']);
            $content=['user'=>$user,'address'=>$address,'cart'=>$userCart];
                 }
                 catch(Exception $e){
                    $content=['error'=>$e];
                    $status=403;
                 }
                 return response($content,$status);
        
    }
    public static function updateAddressid($user_id,$address_id)
    {
        try{
        User::where('id',$user_id)
        ->update(['address_id'=>$address_id]);
        return true;
        }catch(QueryException $e){
            return null;
        }
    }

  


}
