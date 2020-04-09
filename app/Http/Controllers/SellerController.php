<?php

namespace App\Http\Controllers;
use App\Model\Seller;
use Illuminate\Http\Request;
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
			$products=ProductController::getSellerProducts($seller_ids);
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
				$addressId=AddressController::getInsertId((array)$address,'SELLER');
				$content=Seller::insert([
					'name'=>$reqData['name'],
					'uid'=>$reqData['uid'],
					'email'=>$reqData['email'],
					'number'=>$reqData['mobile'],
					'current_status'=>$reqData['current_status'],
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

    
}
