<?php
namespace Controller;

use Model\Line;

class Garage extends AbstractController
{
    /**
     * @var \Service\Garage
     */
    private $_service;
    private $_lineService;

    public function __construct()
    {
        parent::__construct();

        $this->_service = new \Service\Garage();
        $this->_lineService = new \Service\Line();

        if (!isLogged()){
            $this->permissionsErrorAndRedirect();
        }
    }

    /* List all garages here */
    public function defaultView()
    {
        $filter = array(
            'status' => array('created')
        );
        $garages = $this->_service->findAll($filter);

        $this->setModel(array(
            'garages' => $garages
        ));
        return $this->display('garage/default.twig');
    }

    public function addAction()
    {
        $post = $_POST;
        $errors = array();

        if ($post && $this->_validate($post, $errors)) {
            $garage = $this->_service->add($post);

            $garageID = $garage->getID();
            $this->_processLines($post, $garageID);

            $this->addSuccessMsg('New garage was successfully created');

            if (isLogged()){
                $this->redirect('/garage');
            } else {
                $this->redirect('/');
            }
        }

        $garage = isset($_POST) ? $_POST : array();
        $lines = isset($_POST['lines']) ? $_POST['lines'] : array();

        $this->setModel(array(
            'form_action' => '/garage/add',
            'garage' => $garage,
            'lines' => $lines,
            'errors' => $errors
        ));
        return $this->display('garage/form.twig');
    }

    public function editAction($id){
        $post = $_POST;
        $errors = array();

        $garage = $this->_service->findOneById((int)$id);

        if (!$garage){
            $this->addError('Garage was not found!');
            $this->redirect('/garage');
        }

        if ($post && $this->_validate($post, $errors)) {
            $this->_service->update($id, $post);

            $this->_processLines($post, $id);

            $this->addSuccessMsg('Garage was successfully updated');
            $this->redirect('/garage');
        }

        $garage = $_POST ? $_POST : $garage;

        $filter = array(
            'status' => 'created',
            'garageID' => $id
        );
        $lines = isset($_POST['lines']) ? $_POST['lines'] : $this->_lineService->findAll($filter);;

        $this->setModel(array(
            'form_action' => '/garage/edit/' . $id,
            'garage' => $garage,
            'lines' => $lines,
            'errors' => $errors
        ));
        return $this->display('garage/form.twig');
    }

    public function removeAction($id){
        $garage = $this->_service->findOneById((int)$id);

        if ($garage){
            $garage->setStatus('removed');
            $this->_service->saveEntity($garage);
            $this->addSuccessMsg('Garage has been successfully removed');
        } else {
            $this->addError('Garage with id ' . $id . ' was not found!');
        }

        $this->redirect('/garage');
    }

    private function _validate($post, &$errors){
        // TODO: add validation!

        return empty($errors);
    }

    private function _processLines($post, $garageID){
        $lines = $post['lines'];

        // Save info about Lines
        if ($lines){
            foreach ($lines['lineID'] as $counter => $lineID) {
                $lineID = (int)$lineID;
                $line = $this->_lineService->findOneById($lineID);

                // Element should be removed
                //(If the row was created and then deleted but object wasn't created, just skip it)
                if ((int)$lines['remove'][$counter]) {
                    if ($line) {
                        $line->setStatus('removed');
                        $this->_lineService->saveEntity($line);
                    }

                    // Save settings line
                } else {
                    // If line wasn't created, create it
                    if (!$line) {
                        $line = new Line();
                        $line->setGarageID($garageID);
                        $line->setStatus('created');
                    }

                    // Update parameters
                    $canServeVansAndTrucks = !empty($lines['canServeVansAndTrucks'])
                        ? (array_key_exists($counter, $lines['canServeVansAndTrucks']) ? 1 : 0)
                        : 0;
                    $line->setCanServeVansAndTrucks($canServeVansAndTrucks);
                    $line->setUniqueID($lines['uniqueID'][$counter]);

                    $this->_lineService->saveEntity($line);
                }
            }
        }
    }

    public function ajaxLinesByGarageID(){
        $garage_id = isset($_POST['garageID']) ? (int) $_POST['garageID'] : 0;
        $lines = $this->_lineService->findAll(array('status' => 'created', 'garageID' => $garage_id));

        // Convert Objects to Arrays
        $lines = $this->_lineService->toArray($lines);

        header('Content-Type: application/json');
        echo json_encode($lines);
    }
}