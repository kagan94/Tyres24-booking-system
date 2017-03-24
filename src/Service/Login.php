<?php
namespace Service;

class Login
{
    /**
     * @var \Dao\Login
     */
    private $_dao;

    public function __construct()
    {
        $this->setDao(new \Dao\Login());
    }

    public function setDao($dao)
    {
        $this->_dao = $dao;
    }

    public function findAll()
    {
        $users = $this->_dao->getUsers();
        return $users;
    }

    public function findOneById($id)
    {
        $user = $this->_dao->getUser((int)$id);
        return $user;
    }

    public function findByLoginAndPassword($login, $password)
    {
        $login = strip_tags(@$login);
        $password = strip_tags(@$password);

        $user = $this->_dao->getUserByLoginAndPassword($login, $password);
        return $user;
    }

    public function update($post)
    {
        $id = (int)@$post['id'];
        $login = strip_tags(@$post['login']);
        $password = strip_tags(@$post['password']);
        
        $this->_dao->update($id, $login, $password);
    }

    public function delete($id)
    {
        $this->_dao->delete((int)$id);
    }

    public function add($post)
    {
        $login = strip_tags(@$post['login']);
        $password = strip_tags(@$post['password']);
        return $this->_dao->add($login, $password);
    }
}