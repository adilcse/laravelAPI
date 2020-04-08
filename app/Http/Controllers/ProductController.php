<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\Product;
use App\Providers\GenerateToken;
use Illuminate\Database\QueryException;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public static function getSellerProducts($sellers)
    {
        return Product::whereIn('seller_id',$sellers)->get()->toArray();
    }
}
