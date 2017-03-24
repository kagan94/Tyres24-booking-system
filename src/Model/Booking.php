<?php
namespace Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="booking")
 */
class Booking
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
     * @ORM\Column(name="vehicleType")
     *
     * @var int
     */
    private $_vehicleType;

    /**
     * @ORM\Column(name="startDatetime")
     *
     * @var string
     */
    private $_startDatetime;

    /**
     * @ORM\Column(name="endDatetime")
     *
     * @var string
     */
    private $_endDatetime;

    /**
     * @ORM\Column(name="licencePlateNumber")
     *
     * @var string
     */
    private $_licencePlateNumber;

    /**
     * @ORM\Column(name="phoneNumber")
     *
     * @var string
     */
    private $_phoneNumber;

    /**
     * @ORM\Column(name="needTyreStorage")
     *
     * @var int
     */
    private $_needTyreStorage;

    /**
     * @ORM\Column(name="details", length=2000)
     *
     * @var string
     */
    private $_details;

    /**
     * @ORM\Column(name="datetimeAdded")
     *
     * @var string
     */
    private $_datetimeAdded;

    /**
     * @ORM\Column(name="datetimeModified")
     *
     * @var string
     */
    private $_datetimeModified;

//    /**
//     * @ORM\Column(name="garageID")
//     *
//     * @var int
//     */
//    private $_garageID;

//    /**
//     * @ManyToOne(targetEntity="Model/Garage")
//     * @JoinColumn(name="garageID", referencedColumnName="id")
//     */

    /**
     * @ORM\OneToOne(targetEntity="Garage")
     * @ORM\JoinColumn(name="garageID", referencedColumnName="id")
     */
    private $_garage;

    /**
     * @ORM\Column(name="lineID")
     *
     * @var int
     */
    private $_lineID;

    /**
     * @ORM\Column(name="tyresQty")
     *
     * @var int
     */
    private $_tyresQty;

    /**
     * @ORM\Column(name="occupySlots")
     *
     * @var int
     */
    private $_occupySlots;

    /**
     * @ORM\Column(name="status", type="string")
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
    public function getVehicleType()
    {
        return $this->_vehicleType;
    }

    /**
     * @param int $vehicleType
     */
    public function setVehicleType($vehicleType)
    {
        $this->_vehicleType = $vehicleType;
    }

    /**
     * @return string
     */
    public function getStartDatetime()
    {
        return format_datetime($this->_startDatetime);
    }

    /**
     * @param string $startDatetime
     */
    public function setStartDatetime($startDatetime)
    {
        // $startDatetime = date("Y-m-d H:i:s", strtotime($startDatetime));
        $this->_startDatetime = $startDatetime;
    }

    /**
     * @return string
     */
    public function getEndDatetime()
    {
        return format_datetime($this->_endDatetime);
    }

    /**
     * @param string $endDatetime
     */
    public function setEndDatetime($endDatetime)
    {
        // $endDatetime = date("Y-m-d H:i:s", strtotime($endDatetime));
        $this->_endDatetime = $endDatetime;
    }

    /**
     * @return string
     */
    public function getLicencePlateNumber()
    {
        return $this->_licencePlateNumber;
    }

    /**
     * @param string $licencePlateNumber
     */
    public function setLicencePlateNumber($licencePlateNumber)
    {
        $this->_licencePlateNumber = $licencePlateNumber;
    }

    /**
     * @return string
     */
    public function getPhoneNumber()
    {
        return $this->_phoneNumber;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->_phoneNumber = $phoneNumber;
    }

    /**
     * @return int
     */
    public function getNeedTyreStorage()
    {
        return $this->_needTyreStorage;
    }

    /**
     * @param int $needTyreStorage
     */
    public function setNeedTyreStorage($needTyreStorage)
    {
        $this->_needTyreStorage = $needTyreStorage;
    }

    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->_details;
    }

    /**
     * @param string $details
     */
    public function setDetails($details)
    {
        $this->_details = $details;
    }

    /**
     * @return string
     */
    public function getDatetimeAdded()
    {
        return $this->_datetimeAdded;
    }

    /**
     * @param string $datetimeAdded
     */
    public function setDatetimeAdded($datetimeAdded)
    {
        $this->_datetimeAdded = $datetimeAdded;
    }

    /**
     * @return string
     */
    public function getDatetimeModified()
    {
        return $this->_datetimeModified;
    }

    /**
     * @param string $datetimeModified
     */
    public function setDatetimeModified($datetimeModified)
    {
        $this->_datetimeModified = $datetimeModified;
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
     * @return Garage Model
     */
    public function getGarage() { return $this->_garage; }
    public function setGarage($garage) { $this->_garage = $garage; }

    /**
     * @return int
     */
    public function getLineID()
    {
        return $this->_lineID;
    }

    /**
     * @param int $lineID
     */
    public function setLineID($lineID)
    {
        $this->_lineID = (int) $lineID;
    }

    /**
     * @return int
     */
    public function getTyresQty()
    {
        return $this->_tyresQty;
    }

    /**
     * @param int $tyresQty
     */
    public function setTyresQty($tyresQty)
    {
        $this->_tyresQty = (int) $tyresQty;
    }

    /**
     * @return int
     */
    public function getOccupySlots()
    {
        return $this->_occupySlots;
    }

    /**
     * @param int $occupySlots
     */
    public function setOccupySlots($occupySlots)
    {
        $this->_occupySlots = (int) $occupySlots;
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
     * (it can be "booked", "cancelled", "removed")
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
        $text .= ', VehicleType: ' . $this->_vehicleType;
        $text .= ', Start Datetime: ' . $this->_startDatetime;
        $text .= ', End Datetime: ' . $this->_startDatetime;
        $text .= ', Licence Plate Number: ' . $this->_licencePlateNumber;
        $text .= ', Phone Number: ' . $this->_phoneNumber;
        $text .= ', Need Tyre Storage? : ' . $this->_needTyreStorage;
        $text .= ', Details: ' . $this->_details;
        $text .= ', GarageID: ' . $this->_garageID;
        $text .= ', LineID: ' . $this->_lineID;
        $text .= ', TyresQty: ' . $this->_tyresQty;
        $text .= ', OccupySlots: ' . $this->_occupySlots;
        $text .= ', DateAdded: ' . $this->_datetimeAdded;
        $text .= ', DateModified: ' . $this->_datetimeModified;
        $text .= ', Status: ' . $this->_status;
        return $text;
    }
}