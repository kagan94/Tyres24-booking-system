<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="garage")
 */
class Garage
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
     * @ORM\Column(name="name")
     *
     * @var string
     */
    private $_name;

    /**
     * @ORM\Column(name="address")
     *
     * @var string
     */
    private $_address;

    /**
     * @ORM\Column(name="hasTyreStorage")
     *
     * @var int
     */
    private $_hasTyreStorage;

    /**
     * @ORM\Column(name="tyreSlots")
     *
     * @var int
     */
    private $_tyreSlots;

    /**
     * @ORM\Column(name="status")
     *
     * @var string
     */
    private $_status;

    /**
     * @return int
     */
    public function getID()
    {
        return $this->_id;
    }

    /**
     * @param int $id
     */
    public function setID($id)
    {
        $this->_id = $id;
    }

    /**
     * @return int
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param int $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->_address;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->_address = $address;
    }

    /**
     * @return string
     */
    public function getHasTyreStorage()
    {
        return $this->_hasTyreStorage;
    }

    /**
     * @param string $hasTyreStorage
     */
    public function setHasTyreStorage($hasTyreStorage)
    {
        $this->_hasTyreStorage = $hasTyreStorage;
    }

    /**
     * @return string
     */
    public function getTyreSlots()
    {
        return $this->_tyreSlots;
    }

    /**
     * @param string $tyreSlots
     */
    public function setTyreSlots($tyreSlots)
    {
        $this->_tyreSlots = $tyreSlots;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param string $status
     * (Can be either "created" or "removed")
     */
    public function setStatus($status)
    {
        $this->_status = $status;
    }

    /**
     * @return string
     */
    function __toString()
    {
        $text = 'ID: ' . $this->_id;
        $text .= ', Garage name: ' . $this->_name;
        $text .= ', Address: ' . $this->_address;
        $text .= ', HasTyreStorage: ' . $this->_hasTyreStorage;
        $text .= ', TyreSlots: ' . $this->_tyreSlots;
        $text .= ', Status: ' . $this->_status;
        return $text;
    }
}