<?php
namespace Dao;

class Login extends AbstractDao
{
    public function __construct()
    {
        parent::__construct();
        $this->_repository = $this->_em->getRepository('Model\User');
    }

    public function getUser($id)
    {
        $user = $this->_repository->findOneBy(array('_id' => $id));
        $this->_logger->info('User loaded successfully, User details=' . $user);
        return $user;
    }

    public function getUserByLoginAndPassword($login, $password)
    {
        $user = $this->_repository->findOneBy(array('_login' => $login, '_password' => $password));
        $this->_logger->info('User loaded successfully, User details=' . $user);
        return $user;
    }

    public function getUsers()
    {
        $users = $this->_repository->findAll();
        foreach ($users as $user) {
            $this->_logger->info('User::' . $user);
        }
        return $users;
    }

    public function update($id, $login, $password)
    {
        $user = $this->getUser($id);
        $user->setLogin($login);
        $user->setPassword($password);
        $this->_em->persist($user);
        $this->_logger->info('User record updated successfully, User details=' . $user);
    }

    public function delete($id)
    {
        $user = $this->getUser($id);
        $this->_em->remove($user);
        $this->_logger->info('User deleted successfully, User details=' . $user);
        $this->_em->flush();
    }

    // public function add($login, $password)
    // {
    //     $user = new \Model\User();
    //     $user->setDatetime($login);
    //     $user->setDetails($password);
    //     $this->_em->persist($user);
    //     $this->_em->flush($user);
    //     $this->_logger->info('User record saved successfully');
    //     return $user;
    // }
}