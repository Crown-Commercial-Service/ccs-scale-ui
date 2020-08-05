<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestRemoveCacheController extends AbstractController
{
    public function deleteCacheFiles()
    {
        $path = './../var/cache/dev/profiler';
        $poolsPath = './../var/cache/dev/pools';

        if(file_exists($path)){
            echo ' Profiler delete files  <br/>';
            exec("rm -rf $path" , $t);
        
         if(!file_exists($path)){
             echo 'profile cache was removed!!';
         }else{

            echo 'failed to delete caching';
         }

        }

        if(file_exists($poolsPath)){
            echo ' Pools delete files  <br/>';
            exec("rm -rf $poolsPath" , $t);
        
         if(!file_exists($poolsPath)){
             echo 'Pools cache was removed!!';
         }else{

            echo 'failed to delete pools caching';
         }

        }


        die();
    }
}
