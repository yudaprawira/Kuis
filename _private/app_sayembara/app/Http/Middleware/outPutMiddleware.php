<?php

namespace App\Http\Middleware;

use Closure;

class outPutMiddleware
{
    
    /**
     * @var int
     */
    protected $lifeTime = 120;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;

        //$this->removeGarbage();

        return $this->getResponse($next);

        /*$response   = $next($request);
        $buffer     = $response->getContent();
        $compressed = html_compress($buffer);
        $response->setContent($compressed);
        ini_set('zlib.output_compression', 'On');
        // Perform action
        return $response;*/
    }

    protected function getResponse(Closure $next) 
    {
        $cacheKey = $this->request->getPathInfo();

        /*if(!\Cache::has($cacheKey)) {
            $response = $next($this->request);

            $response->original = '';

            \Cache::put($cacheKey, $response, $this->lifeTime);
        }
        else
        {
            $response = \Cache::get($cacheKey);
        }*/
        $response = $next($this->request);
        return $response;
    }

    protected function removeGarbage()
    {
        // grab the cache files
        $d = Storage::disk('fcache')->allFiles();

        // loop the files
        foreach ($d as $key => $cachefile) 
        {
            // ignore that file
            if ($cachefile == '.gitignore') {
                continue;
            }

            // grab the contents of a file
            $d1 = Storage::disk('fcache')->get($cachefile);

            // grab the expire time from the file
            $expire = substr($contents = $d1, 0, 10);

            // check if it has expired
            if (time() >= $expire) {
                // delete the cachefile
                Storage::disk('fcache')->delete($cachefile);
            }
        }
    }
}
