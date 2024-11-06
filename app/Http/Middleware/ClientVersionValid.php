<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Traits\ApiResponder;

class ClientVersionValid
{
    use ApiResponder;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $clients = ['android', 'ios', 'web'];
        $clientType = $request->header('CLIENT-TYPE');
        if ($clientType == 'web') return $next($request);
       

        $clientVersion = $request->header('CLIENT-VERSION');
        $type = $request->header('TYPE');
        if($type == 'provider'){
            $provider = '_provider';
        }else{
            $provider =null;
        }
        

        if (! in_array($clientType, $clients) || ! $clientVersion) {
            return $this->setStatusCode(440)->respondWithError('No Client Found');
        }

        $settings = settings();

        if ($settings["force_update_{$clientType}_version{$provider}"]) {
            $validVersion =
                (int) str_replace('.', '', $clientVersion)
                >=
                (int) str_replace('.', '', $settings["{$clientType}_version{$provider}"]);

            if (! $validVersion) {
                return $this->setStatusCode(441)->respondWithError(__('app.auth.Please update to the latest version'));
            }
        }
        

        return $next($request);
    }
}
