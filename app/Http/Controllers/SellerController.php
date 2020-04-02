<?php

namespace App\Http\Controllers;
use App\Model\Seller;
class SellerController extends Controller
{
        public function getAll()
        {   $content=  Seller::within(22.241497,84.861948,500);
            $status=200;
           return response($result, $status)->header('Access-Control-Allow-Origin','http://localhost:3000');
         
        }
    
}
