<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('catagories')
            ->insert([
                ['name'=>'Grocery & Staples','image'=>'https://firebasestorage.googleapis.com/v0/b/my-grocery-ap.appspot.com/o/catagories%2Fimages%2F41dNC9OcaLL%20-%20Copy.jpg?alt=media&token=ed6547aa-600c-4619-8738-fba8463069ff'],
                ['name'=>'Fruits','image'=>'https://firebasestorage.googleapis.com/v0/b/my-grocery-ap.appspot.com/o/catagories%2Fimages%2Fonline-grocery-gurgaon.jpg?alt=media&token=c93fb397-46b2-4709-a9d8-5282ae9e85b0'],
                ['name'=>'Vegetable','image'=>'https://firebasestorage.googleapis.com/v0/b/my-grocery-ap.appspot.com/o/catagories%2Fimages%2Fc__data_users_defapps_appdata_internetexplorer_temp_sav-500x500.jpg?alt=media&token=2203adff-2c52-4362-a033-9da0d561577a'],
                ['name'=>'Cooking Oils','image'=>'https://firebasestorage.googleapis.com/v0/b/my-grocery-ap.appspot.com/o/catagories%2Fimages%2Foil-ranking-fb.jpg?alt=media&token=e0092781-00a8-4874-9d15-dae699811daf'],
                ['name'=>'Food Grain','image'=>'https://firebasestorage.googleapis.com/v0/b/my-grocery-ap.appspot.com/o/catagories%2Fimages%2FWiB5sGgjBe5tcryxwdak8C-320-80%20-%20Copy.jpg?alt=media&token=189d2213-0fcc-4fbf-b066-d7be8162b889']
            ]
            );
    }
}
