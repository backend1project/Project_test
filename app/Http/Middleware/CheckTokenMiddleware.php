<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Resources\Json;
class CheckTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $error = false;
        if (!$request->hasHeader('X-USER-TOKEN')) {
            $error = true;
        }
        $token = $request->header('X-USER-TOKEN');
        try {
            $jsonStr = base64_decode($token);
            $jsonPayload = json_decode($jsonStr, true);
            if (!$jsonPayload) {
                $error = true;
                dd("kr1");
            }

            if (!isset($jsonPayload['email']) || !isset($jsonPayload['password'])) {
                $error = true;
                dd("kr2");
            }

            $user_email = DB::table('users')
                ->where('Email', '=', $jsonPayload['email'])
                ->where('password','=',$jsonPayload['password'])
                ->get();
            $email=json_decode($user_email,true);
            if ($email['0']['Email']!="io@hotmail.com"){
                $error=true;
            }
        } catch (\Exception $exception) {
            $error = true;

        }
        if ($error) {
            return response()->json(["message" => 'Valid Token'], 401);
        }
        return $next($request);
    }
}
