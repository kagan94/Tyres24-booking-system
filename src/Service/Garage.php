<?php
namespace Service;

class Garage
{
    /**
     * @var \Dao\Garage
     */
    private $_dao;

    public function __construct()
    {
        $this->setDao(new \Dao\Garage());
    }

    public function setDao($dao)
    {
        $this->_dao = $dao;
    }

    public function getFilledSlotsQty($garageID, $filter = array()){
        $filledSlotsQty = $this->_dao->getFilledSlotsQty($garageID, $filter);
        return $filledSlotsQty;
    }

    public function findAll($filter = array())
    {
        $bookings = $this->_dao->getGarages($filter);
        return $bookings;
    }

    public function findOneById($id)
    {
        $booking = $this->_dao->getGarage((int)$id);
        return $booking;
    }

    public function update($id, $post)
    {
        $name = strip_tags(@$post['name']);
        $address = strip_tags(@$post['address']);
        $hasTyreStorage = isset($post['hasTyreStorage']) ? 1 : 0;
        $tyreSlots = $hasTyreStorage ? (int) @$post['tyreSlots'] : 0;

        $this->_dao->update($id, $name, $address, $hasTyreStorage, $tyreSlots);
    }

    // public function delete($id)
    // {
    //     $this->_dao->delete((int)$id);
    // }

    public function add($post)
    {
        $name = strip_tags(@$post['name']);
        $address = strip_tags(@$post['address']);
        $hasTyreStorage = isset($post['hasTyreStorage']) ? 1 : 0;
        $tyreSlots = $hasTyreStorage ? (int) @$post['tyreSlots'] : 0;
        $status = 'created';

        return $this->_dao->add($name, $address, $hasTyreStorage, $tyreSlots, $status);
    }

    public function saveEntity($entity){
        $this->_dao->saveEntity($entity);
    }
}