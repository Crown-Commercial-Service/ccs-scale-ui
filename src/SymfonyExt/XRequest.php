<?php 

namespace App\SymfonyExt;
use Symfony\Component\HttpFoundation\Request;

class XRequest extends Request{

    /**
     * 
     *
     * @return void
     */
    protected function prepareBasePath()
    { 

        return $this->server->get('APP_BASE_PATH', parent::prepareBasePath());
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function prepareBaseUrl()
    {
        return $this->server->get('APP_BASE_URL', parent::prepareBaseUrl());
        
    }
    

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getHost()
    {

        return $this->server->get('APP_HOST', parent::getHost());
        
    }
    
    /**
     * 
     */
    protected function preparePathInfo(){

        if (null === ($requestUri = $this->getRequestUri())) {
            return '/';
        }

        // Remove the query string from REQUEST_URI
        if (false !== $pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }
        if ('' !== $requestUri && '/' !== $requestUri[0]) {
            $requestUri = '/'.$requestUri;
        }

        if (null === ($baseUrl = $this->getBaseUrl())) {
            return $requestUri;
        }

        if ($this->server->get('APP_BASE_URL')) {
            return $requestUri;
        }

        $pathInfo = substr($requestUri, \strlen($baseUrl));
        if (false === $pathInfo || '' === $pathInfo) {
            // If substr() returns false then PATH_INFO is set to an empty string
            return '/';
        }

        return (string) $pathInfo;

    }
}

?>