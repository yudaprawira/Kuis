<?php

namespace App\Http\Middleware;

use Closure;

class cachedMiddleware
{
    /**
     * @var boolean
     */
    protected $isEnabled = false;

    /**
     * @var int
     */
    protected $lifeTime = 7;//minute

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
        
        if($this->isEnabled) $this->removeGarbage();

        return $this->isEnabled ? $this->getResponse($next) : $next($request);
    }

    protected function getResponse(Closure $next) 
    {
        $cacheKey = trim($this->request->getPathInfo(), '/');
        
        if(!\Cache::has($cacheKey)) 
        {
            $response = $next($this->request);

            $response->original = '';

            if ( !$response->exception )
                \Cache::put($cacheKey, $response, $this->lifeTime);
        }
        else
        {
            $response = \Cache::get($cacheKey);
        }
        
        return $response;
    }

    protected function removeGarbage()
    {
        $files = \File::allFiles(storage_path('framework/cache'));
        
        if ( !empty($files) )
        {
            foreach( $files as $f )
            {
                if ( file_exists($f->getpathName()) )
                {
                    $content = file_get_contents($f->getpathName());
                    
                    $expire = substr($content, 0, 10);
                    
                    if (time() >= $expire) {
                        // delete the cachefile
                       unlink($f->getpathName());
                    }

                    $dir = glob($f->getpath().'/*');
                    if ( empty($dir) && is_dir($f->getpath()) ) rmdir($f->getpath());
                }
                
            }
        }

        //delete all directories
        $dirs = glob(storage_path('framework/cache/**'));
        if (!empty( $dirs ))
        {
            foreach( $dirs as $d )
            {
                $dir = glob($d.'/*');
                if ( empty($dir) && is_dir($d) ) @rmdir($d);
            }
        }
    }
}
