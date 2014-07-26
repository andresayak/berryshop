<?php

namespace Admin\Controller;

use Ap\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function indexAction()
    {
        $command = 'ps aux | grep "demon"';
        exec($command, $demonOutput);
        foreach($demonOutput AS $n=>$line){
            if(preg_match('/\bgrep\b/', $line)){
                unset($demonOutput[$n]);
            }
        }
        return array('demonOutput'=>$demonOutput);
    }
}
