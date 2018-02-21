<?php
namespace App\Http\Middleware;

use Closure;
use Session;
use Request;
use App\Http\Controllers\Auth\JWTController;
use \App\Services\AuthService;
use \Firebase\JWT\JWT;

class AuthUserData {

    use JWTController;

    public function handle($request, Closure $next) {
        $token = AuthService::token();
        if (!isset($token)) {
            return redirect('/logout');
        }
        //檢查「Token」
        if (!$this->checkSignature($token)) {
            return redirect('/logout');
        }


        $decodeJWT = JWT::decode($token, base64_decode(secret), array(algo));
        $userdata = $decodeJWT->data;

        //展延「Token」
        //產生新的「Token」
        $newToken = $this->generateJWTToken(AuthService::userData());
        //存入Session
        AuthService::saveToken($newToken, AuthService::userData());


        return $next($request);
    }

}
