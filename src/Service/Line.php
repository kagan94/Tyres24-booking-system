<?php
namespace Service;

class Line
{
    /**
     * @var \Dao\Line
     */
    private $_dao;

    public function __construct()
    {
        $this->setDao(new \Dao\Line());
    }

    public function setDao($dao)
    {
        $this->_dao = $dao;
    }

    public function findAll($filter = array())
    {
        $bookings = $this->_dao->getLines($filter);
        return $bookings;
    }

    public function findOneById($id)
    {
        $booking = $this->_dao->getLine((int)$id);
        return $booking;
    }

    public function update($id, $post)
    {
        $garageID = strip_tags(@$post['garageID']);
        $uniqueID = strip_tags(@$post['uniqueID']);
        $canServeVansAndTrucks = isset($post['canServeVansAndTrucks']) ? 1 : 0;

        $this->_dao->update($id, $garageID, $uniqueID, $canServeVansAndTrucks);
    }

    public function remove($id){
        $this->_dao->remove((int)$id);
    }

    //public function delete($id)
    //{
    //    $this->_dao->delete((int)$id);
    //}

    public function add($post)
    {
        $garageID = strip_tags(@$post['garageID']);
        $uniqueID = strip_tags(@$post['uniqueID']);
        $canServeVansAndTrucks = isset($post['canServeVansAndTrucks']) ? 1 : 0;
        $status = 'created';

        return $this->_dao->add($garageID, $uniqueID, $canServeVansAndTrucks, $status);
    }

    public function toArray($lines){
        return $this->_dao->toArray($lines);
    }

    public function saveEntity($entity){
        $this->_dao->saveEntity($entity);
    }
}