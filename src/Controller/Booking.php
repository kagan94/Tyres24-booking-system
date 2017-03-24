<?php
namespace Controller;

class Booking extends AbstractController
{
    /**
     * @var \Service\Booking
     */
    private $_service;
    private $_serviceGarage;
    private $_serviceLine;

    public function __construct()
    {
        parent::__construct();
        $this->_service = new \Service\Booking();
        $this->_serviceGarage = new \Service\Garage();
        $this->_serviceLine = new \Service\Line();
    }

    /* List all bookings here */
    public function defaultView()
    {
        $this->_checkPermissions();

        $get = $_GET;
        $filter = array(
            'status' => array('booked', 'cancelled'),
            'startDatetimeFrom' => date("Y-m-d H:i:s") // start from now
        );

        // Apply filter
        if ($get){
            $filter['startDatetime'] = $get['startDatetime'] ? $get['startDatetime'] : null;
            $filter['garageID'] = $get['garageID'] ? $get['garageID'] : null;
            $filter['lineID'] = $get['lineID'] ? $get['lineID'] : null;
            $filter['tyresQty'] = $get['tyresQty'] ? $get['tyresQty'] : null;
            $filter['lineType'] = $get['lineType'] ? $get['lineType'] : null;
        }

        // Filter and fetch the bookings
        $bookings = $this->_service->findAll($filter);

        $this->setModel(array(
            'bookings' => $bookings,
            'garages' => $this->_getGarages(),
            'lines' => $get['garageID'] ? $this->_getLines($get['garageID']) : array(),
            'filter' => $filter
        ));
        return $this->display('booking/default.twig');
    }

    public function addAction()
    {
        $post = $_POST;
        $errors = array();

        if ($post && $this->_validate($post, $errors)) {
            $this->_service->add($post);

            $this->addSuccessMsg('New booking was successfully created');
            $this->redirect(isLogged() ? '/booking' : '/');
        }

        // Fetch service lines by the first garage
        $lines = count($this->_getGarages())
            ? $this->_getLines($this->_getGarages()[0]->getID()) // getLines by garageID
            : array();

        // Update endDatetime, because hidden fields are not saved
        $post['endDatetime'] = getFormattedEndDatetime($post);

        $this->setModel(array(
            'booking' => $_POST ? $post : array(),
            'form_action' => '/booking/add',
            'garages' => $this->_getGarages(),
            'lines' => $lines,
            'errors' => $errors
        ));
        return $this->display('booking/form.twig');
    }

    public function editAction($id){
        $this->_checkPermissions();

        $post = $_POST;
        $errors = array();

        $booking = $this->_service->findOneById((int)$id);

        if (!$booking){
            $this->addError('Booking was not found!');
            $this->redirect('/booking');
        }

        if ($post && $this->_validate($post, $errors)) {
            $this->_service->update($id, $post);

            $this->addSuccessMsg('Booking was successfully updated');
            $this->redirect('/booking');
        }

        $lines = $booking->getGarage()->getID() ? $this->_getLines($booking->getGarage()->getID()) : array();

        // Update endDatetime, because hidden fields are not saved
        $post['endDatetime'] = getFormattedEndDatetime($post);

        $this->setModel(array(
            'form_action' => '/booking/edit/' . $id,
            'booking' => $_POST ? $post : $booking,
            'garages' => $this->_getGarages(),
            'lines' => $lines,
            'errors' => $errors
        ));
        return $this->display('booking/form.twig');
    }

    public function cancelAction($id){
        $this->_checkPermissions();

        $booking = $this->_service->findOneById((int)$id);

        if ($booking){
            $booking->setStatus('cancelled');
            $this->_service->saveEntity($booking);

            $this->addSuccessMsg('Booking has been successfully cancelled');
        } else {
            $this->addError('Booking with id ' . $id . ' was not found!');
        }

        $this->redirect('/booking');
    }

    public function removeAction($id){
        $this->_checkPermissions();

        $booking = $this->_service->findOneById((int)$id);

        if ($booking){
            $booking->setStatus('removed');
            $this->_service->saveEntity($booking);
            $this->addSuccessMsg('Booking has been successfully removed');
        } else {
            $this->addError('Booking with id ' . $id . ' was not found!');
        }

        $this->redirect('/booking');
    }

    private function _getGarages(){
        $garages = $this->_serviceGarage->findAll(array('status' => 'created'));
        return $garages;
    }

    private function _getLines($garageID){
        return $this->_serviceLine->findAll(array('status' => 'created', 'garageID' => $garageID));
    }

    private function _validate($post, &$errors){
        /* Check on duplicate */
        $filter = array(
            'status' =>array('booked', 'cancelled'),
            'startDatetime' => $post['startDatetime'],
            'vehicleType' => $post['vehicleType'],
            'licencePlateNumber' => $post['licencePlateNumber'],
            'phoneNumber' => $post['phoneNumber'],
            'lineID' => $post['lineID'],
            'excludeID' => isset($post['id']) && $post['id'] ? $post['id'] : null
        );
        $bookings = $this->_service->findAll($filter);

        if (count($bookings))
            $errors[] = 'It seems that you try to make a double-booking! Please, check you information.';

        /* Check on free time slot */
        $filter = array(
            'status' => array('booked', 'cancelled'),
            'startDatetimeFrom' => $post['startDatetime'],
            'startDatetimeTo' => getFormattedEndDatetime($post),
            // 'lineID' => $post['lineID'],
            'excludeID' => isset($post['id']) && $post['id'] ? $post['id'] : null
        );
        $bookings = $this->_service->findAll($filter);

        if (count($bookings)){
            $error_msg = 'We’re sorry, but this garage has already been booked. ';

            // Search for possible time slots in other garages
            $garages = $this->_serviceGarage->findAll(array('status' => 'created'));
            $possibleGarages = array();

            //unset($filter['lineID']);

            foreach ($garages as $garage){
                if ($garage->getID() == $post['garageID']){
                    continue;
                } else {
                    $filter['garageID'] = $garage->getID();
                    $bookings = $this->_service->findAll($filter);

                    if (!count($bookings)){
                        $possibleGarages[] = $garage->getName();
                    }
                }
            }

            $error_msg .= !empty($possibleGarages) ? 'You could book a time at the following garages: ' . implode(', ', $possibleGarages) : '';
            
            $errors[] = $error_msg;
        }

        /* Check the startDatetime */
        if (strtotime($post['startDatetime']) < time()){
            $errors[] = 'Sorry, you cannot make a booking in the past.';
        }

        /* Check free slots for tyre storage (if needed) */
        if (isset($post['needTyreStorage'])){
            $tyresQty = (int) $post['tyresQty'];
            $garageID = (int) $post['garageID'];

            $garage = $this->_serviceGarage->findOneById($garageID);

            if ($garage->getHasTyreStorage()){
                $needSlots = ceil($tyresQty / 4);
                $filledSlotsQty = $this->_serviceGarage->getFilledSlotsQty($garageID, array(
                    'excludeBookingID' => isset($post['id']) && $post['id'] ? $post['id'] : null
                ));

                if ($filledSlotsQty + $needSlots > $garage->getTyreSlots()){
                    $errors[] = 'Sorry, this garage is full and have no free slots for tyre storage. Please, choose another garage.';
                }
            } else {
                $errors[] = 'Sorry, this garage does not have tyre storage. Choose another garage.';
            }
        }

        return empty($errors);
    }

    private function _checkPermissions(){
        if (!isLogged()){
            $this->permissionsErrorAndRedirect();
        }
    }
}