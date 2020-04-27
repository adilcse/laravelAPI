<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
/**
 * user's controller handle user's CURD operation
 */
class UserController extends Controller
{
    /**
     * store new user in database
     */
    public function store(Request $request)
    {
        $reqData=(array)json_decode($request->input('json'));
        try{
            $id=User::store($reqData);
            if($id){
                $status=200;
                $content=['id'=>$id,'error'=>false];
                return response($content, $status);                              
            }
            else{
                $status= 404;
                $error=['error'=>true,'message'=>'user register failed'];
                return response($error, $status);
        }                              
        }catch(QueryException $ex){ 
            $error=['error'=>true,'message'=>'user register failed'];
            $status= 422;
            return response($ex, $status);
        }
    }


    /**
     * get user details by user id
     * @param id user id
     * @return user_details with address
     */
    public function getByUid()
    {
        
        $userObj = Auth::user();
        $user= ['name'=>$userObj->name,
                'email'=>$userObj->email,
                'user_type'=>$userObj->user_type,
                'uid'=>$userObj->uid,
                'address_id'=>$userObj->address_id,
                'id'=>$userObj->id
                ];
        $content=[];
        $status=200;  
        try{
            $address = AddressController::getUserAddress($user['address_id']);
            $userCart = CartController::getUserCart($user['id']);
            $content=['error'=>false,'user'=>$user,'address'=>$address,'cart'=>$userCart];
        }
        catch(Exception $e){
            $content=['error'=>true,'message'=>$e->getMessage()];
            $status=403;
        }
        
        return response($content,$status);
    }


    /**
     * update user's address id
     */
    public static function updateAddressid($userId,$addressId)
    {
        try{
                User::where('id',$userId)
                ->update(['address_id'=>$addressId]);
                return true;
        }catch(QueryException $e){
            return null;
        }
    }
}
