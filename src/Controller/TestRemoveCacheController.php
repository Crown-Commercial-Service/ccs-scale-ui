<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use \Exception;

class TestRemoveCacheController extends AbstractController
{
    public function deleteCacheFiles()
    {
        $path = './../var/cache/dev/profiler';

        if(file_exists($path)){
            echo 'delete files  <br/>';
         exec("rm -rf $path" , $t);
        


         if(!file_exists($path)){
             echo 'profile cache was removed!!';
         }else{

            echo 'failed to delete caching';
         }

        }
        die();
    }
}
