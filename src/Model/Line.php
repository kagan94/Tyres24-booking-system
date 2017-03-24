<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="line")
 */
class Line
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
     * @ORM\Column(name="garageID")
     *
     * @var integer
     */
    private $_garageID;

    /**
     * @ORM\Column(name="uniqueID")
     *
     * @var integer
     */
    private $_uniqueID;

    /**
     * @ORM\Column(name="canServeVansAndTrucks")
     *
     * @var int
     */
    private $_canServeVansAndTrucks;

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
    public function getGarageID()
    {
        return $this->_garageID;
    }

    /**
     * @param int $garageID
     */
    public function setGarageID($garageID)
    {
        $this->_garageID = $garageID;
    }

    /**
     * @return int
     */
    public function getUniqueID()
    {
        return $this->_uniqueID;
    }

    /**
     * @param int $uniqueID
     */
    public function setUniqueID($uniqueID)
    {
        $this->_uniqueID = $uniqueID;
    }

    /**
     * @return string
     */
    public function getCanServeVansAndTrucks()
    {
        return $this->_canServeVansAndTrucks;
    }

    /**
     * @param string $canServeVansAndTrucks
     */
    public function setCanServeVansAndTrucks($canServeVansAndTrucks)
    {
        $this->_canServeVansAndTrucks = $canServeVansAndTrucks;
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
        $text .= ', GarageID: ' . $this->_garageID;
        $text .= ', UniqueID: ' . $this->_uniqueID;
        $text .= ', CanServeVansAndTrucks: ' . $this->_canServeVansAndTrucks;
        $text .= ', Status: ' . $this->_status;
        return $text;
    }

    /**
     * @return array
     */
    function toArray()
    {
        return array(
            'id' => $this->_id,
            'garageID' => $this->_garageID,
            'uniqueID' => $this->_uniqueID,
            'canServeVansAndTrucks' => $this->_canServeVansAndTrucks,
            'status' => $this->_status
        );
    }
}