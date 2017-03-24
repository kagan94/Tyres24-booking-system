<?php
namespace Dao;

class Line extends AbstractDao
{
    public function __construct()
    {
        parent::__construct();
        $this->_repository = $this->_em->getRepository('Model\Line');
    }

    public function getLine($id)
    {
        $line = $this->_repository->findOneBy(array('_id' => (int)$id));
        $this->_logger->info('Line loaded successfully, Line details=' . $line);
        return $line;
    }

    public function getLines($filter = array())
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->from('Model\Line', 'l')
            ->select('l')
            ->orderBy('l._id', 'DESC');

        if (isset($filter['status'])) {
            $qb->andWhere("l._status IN (:status)")
                ->setParameter('status', $filter['status']);
        }

        if (isset($filter['garageID'])) {
            $qb->andWhere("l._garageID IN (:garageID)")
                ->setParameter('garageID', $filter['garageID']);
        }

        $lines = $qb->getQuery()->execute();

        foreach ($lines as $line) {
            $this->_logger->info('Line::' . $line);
        }

        return $lines;
    }

    public function update($id, $garageID, $uniqueID, $canServeVansAndTrucks)
    {
        $line = $this->getLine($id);
        $line->setGarageID($garageID);
        $line->setUniqueID($uniqueID);
        $line->setCanServeVansAndTrucks($canServeVansAndTrucks);

        $this->_em->persist($line);
        $this->_logger->info('Line record updated successfully, Line details=' . $line);
    }

    /* Set the status of the Entity to 'removed' */
    public function remove($id){
        $line = $this->getLine($id);
        $line->setStatus('removed');

        $this->saveEntity($line);
        $this->_logger->info('Line removed successfully, Garage details=' . $line);
    }

    // public function delete($id)
    // {
    //     $line = $this->getLine($id);
    //     $this->_em->remove($line);
    //     $this->_logger->info('Line deleted successfully, Line details=' . $line);
    //     $this->_em->flush();
    // }

    public function add($garageID, $uniqueID, $canServeVansAndTrucks, $status)
    {
        $line = new \Model\Line();
        $line->setGarageID($garageID);
        $line->setUniqueID($uniqueID);
        $line->setCanServeVansAndTrucks($canServeVansAndTrucks);
        $line->setStatus($status);

        $this->_em->persist($line);
        $this->_em->flush($line);
        $this->_logger->info('Line record saved successfully');
        return $line;
    }

    public function toArray(&$lines){
        foreach ($lines as $key => $line){
            $lines[$key] = $line->toArray();
        }
        return $lines;
    }
}