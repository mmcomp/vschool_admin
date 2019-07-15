<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json([
                    "status"=>0,
                    "messages"=>[
                        [
                            "code"=>"TokenIsInvalid",
                            "message"=>"توکن شما صحیح نیست"
                        ]
                    ],
                    "data"=>[]
                ]);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json([
                    "status"=>0,
                    "messages"=>[
                        [
                            "code"=>"TokenExpired",
                            "message"=>"توکن شما منقضی شده است"
                        ]
                    ],
                    "data"=>[]
                ]);
            }else{
                return response()->json([
                    "status"=>0,
                    "messages"=>[
                        [
                            "code"=>"TokenNotFound",
                            "message"=>"توکن شما پیدا نشد"
                        ]
                    ],
                    "data"=>[]
                ]);
            }
        }
        return $next($request);
    }
}
