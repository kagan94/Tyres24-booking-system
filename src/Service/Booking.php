<?php
namespace Service;

class Booking
{
    /**
     * @var \Dao\Booking
     */
    private $_dao;

    public function __construct()
    {
        $this->setDao(new \Dao\Booking());
    }

    public function setDao($dao)
    {
        $this->_dao = $dao;
    }

    public function findAll($filter = array())
    {
        $bookings = $this->_dao->getBookings($filter);
        return $bookings;
    }

    public function findOneById($id)
    {
        $booking = $this->_dao->getBooking((int)$id);
        return $booking;
    }

    public function update($id, $post, $status="booked")
    {
        $vehicleType = strip_tags(@$post['vehicleType']);
        $licencePlateNumber = strip_tags(@$post['licencePlateNumber']);
        $phoneNumber = strip_tags(@$post['phoneNumber']);
        $needTyreStorage = isset($post['needTyreStorage']) ? 1 : 0;
        $details = strip_tags(@$post['details']);

        $garageID = (int)@$post['garageID'];
        $lineID = (int)@$post['lineID'];
        $tyresQty = (int)@$post['tyresQty'];
        $occupySlots = $needTyreStorage ? ceil($tyresQty / 4) : 0;

        $startDatetime = date("Y-m-d H:i:s", strtotime($post['startDatetime']));
        $endDatetime =  date("Y-m-d H:i:s", strtotime(getFormattedEndDatetime($post)));

        $datetimeModified = date("Y-m-d H:i:s", time()); // Update modification date

        $this->_dao->update($id, $vehicleType, $startDatetime, $endDatetime, $licencePlateNumber, $phoneNumber, $needTyreStorage, $details, $garageID, $lineID, $tyresQty, $occupySlots, $status, $datetimeModified);
    }


    public function delete($id)
    {
        $this->_dao->delete((int)$id);
    }

    public function add($post)
    {
        $vehicleType = strip_tags(@$post['vehicleType']);
        $licencePlateNumber = strip_tags(@$post['licencePlateNumber']);
        $phoneNumber = strip_tags(@$post['phoneNumber']);
        $needTyreStorage = isset($post['needTyreStorage']) ? 1 : 0;
        $details = strip_tags(@$post['details']);

        $garageID = (int)$post['garageID'];
        $lineID = (int)@$post['lineID'];
        $tyresQty = (int)@$post['tyresQty'];
        $occupySlots = $needTyreStorage ? ceil($tyresQty / 4) : 0;
        $status = 'booked';

        $datetimeAdded = date("Y-m-d H:i:s", time());
        $startDatetime = date("Y-m-d H:i:s", strtotime($post['startDatetime']));
        $endDatetime =  date("Y-m-d H:i:s", strtotime(getFormattedEndDatetime($post)));

        return $this->_dao->add($vehicleType, $startDatetime, $endDatetime, $licencePlateNumber, $phoneNumber, $needTyreStorage, $details, $garageID, $lineID, $tyresQty, $occupySlots, $status, $datetimeAdded);
    }

    public function saveEntity($booking){
        // Also update datetimeModified param
        $booking->datetimeModified = date("Y-m-d H:i:s", time());
        $this->_dao->saveEntity($booking);
    }
}