<?php

namespace App\Http\Controllers;
use App\Model\Catagory;

class AllCatagory extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function getAll()
    {   $content=  Catagory::getAllCatagory();
        $status=200;
       return response($content, $status)->header('Access-Control-Allow-Origin','http://localhost:3000');
     
    }

    //
}
