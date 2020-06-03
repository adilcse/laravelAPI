<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;

class GenerateToken extends ServiceProvider
{
    private static $auth;
    public function register()
    {
        self::$auth =  app('firebase.auth');
    }

    public static function getToken($id,$email,$name)
    {
        $uid=strval($id);
    $additionalClaims = [
        'email' => $email,
        'name'=>$name,
        ];
    $customToken = self::$auth->createCustomToken($uid, $additionalClaims);
    $customTokenString = (string) $customToken;
        return $customTokenString;
    }
}