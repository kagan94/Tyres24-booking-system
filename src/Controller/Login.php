<?php
namespace Controller;

class Login extends AbstractController {
    private $_service;

    public function __construct()
    {
        parent::__construct();
        $this->setService(new \Service\Login());
    }

    public function setService($service)
    {
        $this->_service = $service;
    }

    public function defaultView()
    {
        $post = $_POST;

        // If the user already logged in, redirect to the main page..
        if (isset($_SESSION['login'])){
            $this->redirect('/');
        }

        if ($post && isset($post['login'], $post['password'])) {
            $login = $post['login'];
            $user = $this->_service->findByLoginAndPassword($login, $post['password']);

            if ($user) {
                $_SESSION['login'] = $login;
                $_SESSION['user_id'] = $user->getId();
                $this->addSuccessMsg(sprintf("Welcome, \"%s\"!", $login));

                // Redirect to main page
                $this->redirect('/');
            } else {
                $this->addError("Login/Password is incorrect");
            }
        }

        // $this->setModel(array('errors' => $errors));
        return $this->display('login/default.twig');
    }

    public function exitAction(){
        if (isset($_SESSION['login']))
            unset($_SESSION['login']);
        if (isset($_SESSION['user_id']))
            unset($_SESSION['user_id']);

        $this->addSuccessMsg(sprintf("You successfully logged out"));
        $this->redirect('/login');
    }
}