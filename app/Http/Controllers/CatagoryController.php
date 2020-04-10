<?php

namespace App\Http\Controllers;
use Illuminate\Database\QueryException;
use App\Model\Catagory;
class CatagoryController extends Controller
{
  public function getAll()
  {
      try{
          $content=Catagory::getAllCatagory();
          return response(['catagory'=>$content],200);
      }
      catch(QueryException $e)
      {
        return response(['error'=>$e],200);
      }
  }
}
