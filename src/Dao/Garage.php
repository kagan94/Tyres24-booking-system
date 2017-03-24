<?php
namespace Dao;

class Garage extends AbstractDao
{
    public function __construct()
    {
        parent::__construct();
        $this->_repository = $this->_em->getRepository('Model\Garage');
    }

    public function getFilledSlotsQty($garageID, $filter = array()){
        $sql = 'SELECT count(id) AS `total` FROM booking b WHERE b.status = \'booked\'';

        if (isset($filter['excludeBookingID']) && $filter['excludeBookingID']){
            $sql .= ' AND b.id != ' . $filter['excludeBookingID'];
        }

        $result = $this->_em->getConnection()->fetchAll($sql);
        return $result[0]['total'];
    }

    public function getGarage($id)
    {
        $garage = $this->_repository->findOneBy(array('_id' => (int)$id));
        $this->_logger->info('Garage loaded successfully, Garage details=' . $garage);
        return $garage;
    }

    public function getGarages($filter = array())
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->from('Model\Garage', 'b')
            ->select('b')
            ->orderBy('b._id', 'DESC');

        if (isset($filter['status'])) {
            $qb->andWhere("b._status IN (:status)")->setParameter('status', $filter['status']);
        }

        $garages = $qb->getQuery()->execute();

        // Create empty instance to fetch connected lines
        $lineInstance = new \Dao\Line();

        foreach ($garages as $key => $garage) {
            $garages[$key]->lines = $lineInstance->getLines(array('status' => 'created', 'garageID' => $garage->getID()));
            $this->_logger->info('Garage::' . $garage);
        }

        return $garages;
    }

    public function update($id, $name, $address, $hasTyreStorage, $tyreSlots)
    {
        $garage = $this->getGarage($id);
        $garage->setName($name);
        $garage->setAddress($address);
        $garage->setHasTyreStorage($hasTyreStorage);
        $garage->setTyreSlots($tyreSlots);

        $this->_em->persist($garage);
        $this->_logger->info('Garage record updated successfully, Garage details=' . $garage);
    }

    /* Set the status of the Entity to 'removed' */
    public function remove($id){
        $garage = $this->getGarage($id);
        $garage->setStatus('removed');

        $this->saveEntity($garage);
        $this->_logger->info('Garage removed successfully, Garage details=' . $garage);
    }

    // public function delete($id)
    // {
    //     $garage = $this->getGarage($id);
    //     $this->_em->remove($garage);
    //     $this->_logger->info('Garage deleted successfully, Garage details=' . $garage);
    //     $this->_em->flush();
    // }

    public function add($name, $address, $hasTyreStorage, $tyreSlots, $status)
    {
        $garage = new \Model\Garage();
        $garage->setName($name);
        $garage->setAddress($address);
        $garage->setHasTyreStorage($hasTyreStorage);
        $garage->setTyreSlots($tyreSlots);
        $garage->setStatus($status);

        $this->_em->persist($garage);
        $this->_em->flush($garage);
        $this->_logger->info('Garage record saved successfully');
        return $garage;
    }
}