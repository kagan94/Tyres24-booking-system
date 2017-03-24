<?php
namespace Dao;

class Booking extends AbstractDao
{
    private $_garageEntity;

    public function __construct()
    {
        parent::__construct();
        $this->_repository = $this->_em->getRepository('Model\Booking');

        $this->_garageEntity = new Garage();
    }

    public function getBooking($id)
    {
        $booking = $this->_repository->findOneBy(array('_id' => (int)$id));
        $this->_logger->info('Booking loaded successfully, Booking details=' . $booking);
        return $booking;
    }

    public function getBookings($filter = array())
    {
        // $bookings = $this->_repository->findAll();

        //         $qb = $this->_em->createQueryBuilder();
        //         $bookings = $qb
        //             ->select('b.name')
        //             ->from('booking', 'b')
        // ////            ->where('b.status <> :status')
        // ////            ->setParameter('status', 'booked')
        // //            ->orderBy('b.id')
        //             ->getQuery();
        // //        $q = $this->_repository->findBy(array());

        // //        $q = $bookings->execute();
        // //        $q->where
        //         echo var_dump($bookings->getResult());
        // $r = $this->_em->createQuery("SELECT u FROM Model\Booking u WHERE u._id<>14")->execute();
        // $q = $this->_repository->findBy(array('_status' => array('booked', 'cancelled')));
        // ->where("b._id <> :id")
        // ->andWhere("b._details <> :details")
        // ->setParameter('id', 10)
        // ->setParameter('details', 'fdfsdfsd');
        // echo var_dump($qb->getQuery());

        // $qb = $this->_em->createQueryBuilder();
        // $q = $qb->update('models\User', 'u')
        //     ->set('u.username', $qb->expr()->literal($username))
        //     ->set('u.email', $qb->expr()->literal($email))
        //     ->where('u.id = ?1')
        //     ->setParameter(1, $editId)
        //     ->getQuery();
        // $p = $q->execute();
        // $r = $qb->getQuery()->getResult();

        $qb = $this->_em->createQueryBuilder();
        $qb->from('Model\Booking', 'b')
            ->select('b')
            ->orderBy('b._startDatetime', 'ASC');
            // ->leftJoin('Model\Garage', 'g')
            // ->where('g._id = b._garage')
            // ->andWhere('g._status = \'created\'');

        // Exclude id (e.g. when we need to check the time slot for existing booking, then we have to exclude it)
        if (isset($filter['excludeID']) && $filter['excludeID']) {
            $qb->andWhere("b._id != :excludeID")->setParameter('excludeID', $filter['excludeID']);
        }

        if (isset($filter['status']) && $filter['status']) {
            $qb->andWhere("b._status IN (:status)")->setParameter('status', $filter['status']);
        }

        if (isset($filter['startDatetime']) && $filter['startDatetime']) {
            $startDatetime = date("Y-m-d H:i:s", strtotime($filter['startDatetime']));
            $qb->andWhere("b._startDatetime = :startDatetime")->setParameter('startDatetime', $startDatetime);
        }

        $startDatetimeFrom = isset($filter['startDatetimeFrom']) ? date("Y-m-d H:i:s", strtotime($filter['startDatetimeFrom'])) : null;
        $startDatetimeTo = isset($filter['startDatetimeTo']) ? date("Y-m-d H:i:s", strtotime($filter['startDatetimeTo'])) : null;

        // Filter by startDatetime datetime range from "X" to "Y"
        if ($startDatetimeFrom || $startDatetimeTo) {
            if (!is_null($startDatetimeFrom) and is_null($startDatetimeTo)) {
                $qb->andWhere("b._startDatetime >= :startDatetimeFrom")->setParameter('startDatetimeFrom', $startDatetimeFrom);

            } else if (is_null($startDatetimeFrom) and !is_null($startDatetimeTo)) {
                $qb->andWhere("b._startDatetime <= :startDatetimeTo")->setParameter('startDatetimeTo', $startDatetimeTo);

            } else if (!is_null($startDatetimeFrom) and !is_null($startDatetimeTo)) { // both (from, to) were specified
                $qb->andWhere("b._startDatetime >= :startDatetimeFrom")
                    ->andWhere("b._startDatetime <= :startDatetimeTo")
                    ->setParameter('startDatetimeFrom', $startDatetimeFrom)
                    ->setParameter('startDatetimeTo', $startDatetimeTo);
            }
        }

        if (isset($filter['garageID']) && $filter['garageID']) {
            $qb->andWhere("b._garage = :garageID")->setParameter('garageID', $filter['garageID']);
        }

        if (isset($filter['vehicleType']) && $filter['vehicleType']) {
            $qb->andWhere("b._vehicleType = :vehicleType")->setParameter('vehicleType', $filter['vehicleType']);
        }

        if (isset($filter['licencePlateNumber']) && $filter['licencePlateNumber']) {
            $qb->andWhere("b._licencePlateNumber = :licencePlateNumber")->setParameter('licencePlateNumber', $filter['licencePlateNumber']);
        }

        if (isset($filter['phoneNumber']) && $filter['phoneNumber']) {
            $qb->andWhere("b._phoneNumber = :phoneNumber")->setParameter('phoneNumber', $filter['phoneNumber']);
        }

        if (isset($filter['lineID']) && $filter['lineID']) {
            $qb->andWhere("b._lineID = :lineID")->setParameter('lineID', $filter['lineID']);
        }

        if (isset($filter['tyresQty']) && $filter['tyresQty']) {
            $qb->andWhere("b._tyresQty = :tyresQty")->setParameter('tyresQty', $filter['tyresQty']);
        }

        if (isset($filter['lineType']) && $filter['lineType']) {
            if ($filter['lineType'] == 'carsOnly'){
                $qb->leftJoin('Model\Line', 'l', \Doctrine\ORM\Query\Expr\Join::WITH, 'b._lineID = l._id');
                $qb->andWhere("l._canServeVansAndTrucks = :canServeVansAndTrucks")
                    ->andWhere("l._status != 'removed'")
                    ->setParameter('canServeVansAndTrucks', 0);
            }
        }
//        $sql = $qb->getQuery()->getParameters();
//        echo '<pre>';
//        echo var_dump($sql);
//        echo '</pre>';
        $bookings = $qb->getQuery()->execute();

        foreach ($bookings as $booking) {
            $this->_logger->info('Booking::' . $booking);
        }

        return $bookings;
    }

    public function update($id, $vehicleType, $startDatetime, $endDatetime, $licencePlateNumber, $phoneNumber, $needTyreStorage, $details, $garageID, $lineID, $tyresQty, $occupySlots, $status, $datetimeModified)
    {
        $booking = $this->getBooking($id);
        $booking->setVehicleType($vehicleType);
        $booking->setStartDatetime($startDatetime);
        $booking->setEndDatetime($endDatetime);
        $booking->setLicencePlateNumber($licencePlateNumber);
        $booking->setPhoneNumber($phoneNumber);
        $booking->setNeedTyreStorage($needTyreStorage);
        $booking->setDetails($details);
        $booking->setGarage($this->_garageEntity->getGarage($garageID));
        // $booking->setGarageID($garageID);
        $booking->setLineID($lineID);
        $booking->setTyresQty($tyresQty);
        $booking->setOccupySlots($occupySlots);
        $booking->setStatus($status);

        $booking->setDatetimeModified($datetimeModified);

        $this->_em->persist($booking);
        $this->_logger->info('Booking record updated successfully, Booking details=' . $booking);
    }

    public function delete($id)
    {
        $booking = $this->getBooking($id);
        $this->_em->remove($booking);
        $this->_logger->info('Booking deleted successfully, Booking details=' . $booking);
        $this->_em->flush();
    }

    public function add($vehicleType, $startDatetime, $endDatetime, $licencePlateNumber, $phoneNumber, $needTyreStorage, $details, $garageID, $lineID, $tyresQty, $occupySlots, $status, $datetimeAdded)
    {
        $booking = new \Model\Booking();
        $booking->setVehicleType($vehicleType);
        $booking->setStartDatetime($startDatetime);
        $booking->setEndDatetime($endDatetime);
        $booking->setLicencePlateNumber($licencePlateNumber);
        $booking->setPhoneNumber($phoneNumber);
        $booking->setNeedTyreStorage($needTyreStorage);
        $booking->setDetails($details);
        $booking->setGarage($this->_garageEntity->getGarage($garageID));
        //$booking->setGarage($garageID);
        $booking->setLineID($lineID);
        $booking->setTyresQty($tyresQty);
        $booking->setOccupySlots($occupySlots);
        $booking->setStatus($status);

        $booking->setDatetimeAdded($datetimeAdded);

        $this->_em->persist($booking);
        $this->_em->flush($booking);
        $this->_logger->info('Booking record saved successfully');
        return $booking;
    }
}