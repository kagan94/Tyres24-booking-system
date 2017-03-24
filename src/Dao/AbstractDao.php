<?php
namespace Dao;

use Monolog\Handler\ErrorLogHandler;
use Monolog\Logger;

abstract class AbstractDao
{
    protected $_logger;
    protected $_em;
    protected $_repository;

    public function __construct()
    {
        $this->_logger = new Logger(get_class($this));
        $this->_logger->pushHandler(new ErrorLogHandler());
        $this->_em = EntityManager::getInstance()->getManager();
    }

    public function saveEntity($entity){
        $this->_em->persist($entity);
        $this->_em->flush($entity);
        $this->_logger->info(get_class($this) . ' saved successfully');
    }

    function __destruct()
    {
        $this->_em->flush();
    }
}