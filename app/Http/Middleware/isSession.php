<?php

namespace App\Http\Middleware;
use Illuminate\Http\Response;
use Closure;
use Auth;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;

class isSession
{
   
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $message = '';
        $code = Response::HTTP_UNAUTHORIZED;
        if(Auth::user() === null){
            exit($this->errorResponse('',''));
        }
        return $next($request);
    }
}
