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
        //var_dump($this->server->get('APP_HOST'));die('xxx');
        return $this->server->get('APP_HOST', parent::getHost());
        
    }
    
}

?>