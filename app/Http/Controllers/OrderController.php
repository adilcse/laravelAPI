<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Model\User;
use App\Providers\GenerateToken;
use Illuminate\Database\QueryException;
use App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function store(Request $request)
    {
        $reqData=(array)json_decode($request->input('json'));
        
        try{
            $id=User::store($reqData);
        }
        catch(QueryException $e){
            return response([status=>$e],403);
        }
    }

}