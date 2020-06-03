<?php

namespace App\Http\Controllers;
use App\Model\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
/**
 * handle seller API calls
 */
class SellerController extends Controller
{
	/**
	 * get nearby seller of given location
	 * @param request by user
	 * @return details of sellers
	 */
	public function getNearby(Request $request)
	{ 
		$lat=$request->input('lat');
		$lng=$request->input('lng');
		$radius=$request->input('radius');
		//get seller within a fixed radius
		$content= Seller::within($lat,$lng,$radius);
		$seller_ids=[];
		foreach ($content as $value) {
			array_push($seller_ids,$value->id);
		};
		//get products of fetched sellers
		$products=ProductController::getSellersProducts($seller_ids);
		//calculate price and discount
		foreach($products as $key=>$value){
			$value['MRP']=$value["price"];
			$value['price']=$value['price'] - floor($value['price'] * $value['discount']/100);
			$products[$key]=$value;
		}
		$status=200;
		return response(['error'=>false,'sellers'=>$content,'products'=>$products], $status);	
	}	


	/**
	 * register a new seller
	 * for now new user is in ACTIVE state
	 * but it will be in PENDING state and after approval from ADMIN it gets promoted to ACTIVE state
	 * @param Request user's details 
	 */
	public function register(Request $request)
	{
		$reqData=(array)json_decode($request->input('json'));
		$address=$reqData['address'];
		$result=[];
		$code=200;
		try{
			$addressId=AddressController::saveAddress((array)$address,'SELLER');
			$content=Seller::insert([
				'name'=>$reqData['name'],
				'uid'=>$reqData['uid'],
				'email'=>$reqData['email'],
				'number'=>$reqData['mobile'],
				'current_status'=>'ACTIVE',
				'address_id'=>$addressId
			]);
			$result=['error'=>false,'status'=>$content];
			$code=200;
		}
		catch(Exception $e){
			$result=['error'=>true,'message'=>$e];
			$code=403;
		}
		return response($result,$code);
	}


	/**
	 * seller login, verify id and returns selelr details
	 */
	public function login()
	{
		$userObj=Auth::user();
		$seller=['name'=>$userObj->name,
		'email'=>$userObj->email,
		'number'=>$userObj->number,
		'uid'=>$userObj->uid,
		'address_id'=>$userObj->address_id,
		'id'=>$userObj->id,
		'current_status'=>$userObj->current_status,
		];
		try{
			$address = AddressController::getUserAddress($seller['address_id']); 
			$content=array_merge($seller,['address'=>$address]);
			$status=200;
		}
		catch(Exception $e){
			$content=['error'=>true,'message'=>$e];
			$status=403;
		}
		return response(['error'=>false,'data'=>$content],$status);
	}  
}
