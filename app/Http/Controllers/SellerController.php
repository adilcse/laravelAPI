<?php

namespace App\Http\Controllers;
use App\Model\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Http\Controllers;
class SellerController extends Controller
{
        public function getNearby(Request $request)
        { 
			$lat=$request->input('lat');
			$lng=$request->input('lng');
			$radius=$request->input('radius');
			$content= Seller::within($lat,$lng,$radius);
		
			$seller_ids=[];
			foreach ($content as $value) {
				array_push($seller_ids,$value->id);
			};
			
			$products=ProductController::getSellersProducts($seller_ids);
			foreach($products as $key=>$value){
				$value['MRP']=$value["price"];
				$value['price']=$value['price'] - floor($value['price'] * $value['discount']/100);
				$products[$key]=$value;
			}
		
            $status=200;
           return response(['sellers'=>$content,'products'=>$products], $status);
         
		}	
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
					//'current_status'=>$reqData['current_status'],
					'current_status'=>'ACTIVE',
					'address_id'=>$addressId
				]);
				$result=['status'=>$content];
				$code=200;

			}
			catch(Exception $e){
				$result=['error'=>$e];
				$code=403;
			}
			return response($result,$code);
			

		}
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
			$content=['error'=>$e];
			$status=403;
		 }
		 return response($content,$status);
		}

    
}
