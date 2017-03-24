<?php
/**
 * Created by PhpStorm.
 * User: markus
 * Date: 9.03.17
 * Time: 12:47
 */

namespace Controller;

abstract class AbstractController
{
    protected $_model;

    public function __construct()
    {
        $this->_model = array();
    }

    /**
     * Display the Twig template with model
     *
     * @param $templateFileName
     *
     * @return string HTML template
     */
    public function display($templateFileName)
    {
        //$this->_model = array_merge($this->_model, array('session' => $_SESSION));
        $this->_model = array_merge($this->_model, array('session' => $_SESSION));
        return $this->_getTwig()->render($templateFileName, $this->_model);
    }

    /**
     * Set view model
     *
     * @param $model
     */
    protected function setModel($model)
    {
        $this->_model = $model;
    }

    /**
     * Initialize and return Twig element
     *
     * @return \Twig_Environment
     */
    private function _getTwig()
    {
        $loader = new \Twig_Loader_Filesystem(REAL_PATH . 'docroot/html');
        $twig = new \Twig_Environment($loader, array('debug' => true));
        // $twig->addGlobal('session', $_SESSION);

        $this->_cleanNotifications();

        $twig->addExtension(new \Twig_Extension_Debug());
        return $twig;
    }

    protected function redirect($url){
        header('Location: ' . $url);
        exit();
    }

    /* Add error into the session errors array */
    protected function addError($error){
        if (!isset($_SESSION['errors'])){
            $_SESSION['errors'] = array();
        }
        $_SESSION['errors'][] = $error;
    }

    protected function permissionsErrorAndRedirect() {
        $this->addError('You do not have permissions to view this section!');
        $this->redirect('/error/');
    }

    protected function addSuccessMsg($msg){
        if (!isset($_SESSION['success_msgs'])){
            $_SESSION['success_msgs'] = array();
        }
        $_SESSION['success_msgs'][] = $msg;
    }

    private function _cleanNotifications(){
        // If there were some errors, clean them after reloading
        if ($_SESSION['errors']) {
            unset($_SESSION['errors']);
        }

        if ($_SESSION['success_msgs']) {
            unset($_SESSION['success_msgs']);
        }
    }

}