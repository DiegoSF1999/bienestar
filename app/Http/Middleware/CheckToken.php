<?php

namespace App\Http\Middleware;

use Closure;
use App\users;
use App\Helpers\Token;

class CheckToken
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
            $token_inv = new Token();
            $coded_token = $request->header('token');
            $decoded_token = $token_inv->decode_token($coded_token);
            $user = users::where('email', $decoded_token[0])->first();
            if($decoded_token[1] == $user->changed)
            {
                return $next($request);
            } else {
                return 401;
            }
         } catch (\Throwable $th) {
         return 401;
         }
    }
}
