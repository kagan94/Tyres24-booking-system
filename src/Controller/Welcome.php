<?php
/**
 * Created by PhpStorm.
 * User: Leo
 * Date: 17.03.2017
 * Time: 22:58
 */

namespace Controller;


class Welcome extends AbstractController
{
    public function defaultView()
    {
        #$this->setModel(array('isLogged' => isset($_SESSION['login'])));
        return $this->display('welcome.twig');
    }

    public function errorView(){
        return $this->display('error.twig');
    }
}