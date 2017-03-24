<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $_id;

    /**
     * @ORM\Column(name="login", length=20)
     *
     * @var string
     */
    private $_login;

    /**
     * @ORM\Column(name="password", length=20)
     *
     * @var string
     */
    private $_password;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->_login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->_login = $login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return 'ID: ' . $this->_id . ', Login: ' . $this->_login . ', Password: ' . $this->_password;
    }

}