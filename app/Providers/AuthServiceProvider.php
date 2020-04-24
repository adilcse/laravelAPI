<?php

namespace App\Providers;

use App\User;
use App\Model\Seller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Firebase\Auth\Token\Exception\InvalidToken;

class AuthServiceProvider extends ServiceProvider
{
    private $auth ;
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->auth= app('firebase.auth');
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            $token=$request->input('api_token');
            if ($token) { 
                $verifiedIdToken=null;
                try {
                    //verify token by firebase auth
                    $verifiedIdToken = $this->auth->verifyIdToken($token);
                    $uid = $verifiedIdToken->getClaim('sub');
                    if($request->is('user/*')){
                        if($request->is('user/register')){
                            $reqData=json_decode($request->input('json'));
                            if($uid===$reqData->uid)
                                return true;
                            else
                                return null;
                        }
                        return USER::where('uid',$uid)->first();
                    }
                    else if($request->is('seller/*')){
                        if($request->is('seller/register')){
                            $reqData=json_decode($request->input('json'));
                            if($uid===$reqData->uid)
                                return true;
                            else
                                return null;
                        } 
                        return Seller::where('uid',$uid)->first();
                    }
                } catch (\InvalidArgumentException $e) {
                    return null;
                } catch (InvalidToken $e) {
                    return null;
                }    
            }
        });
    }
}
