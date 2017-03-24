<?php

namespace Dao;

use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\ORM\Tools\Setup;

/**
 * Class EntityManager is a singleton-wrapper for doctrine entity manager. After first invocation Doctrines entity manager
 * is accessible from memory. This makes sure that no multiple entity managers are in use.
 * @see http://stackoverflow.com/questions/19273870/doctrine2-a-new-entity-was-found-through-the-relationship
 * @package Dao
 */
class EntityManager
{
    private static $instance;
    private $em;

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    private function __construct()
    {
        global $connectionOptions;
        $paths = array(REAL_PATH . 'src/Model');
        $config = Setup::createAnnotationMetadataConfiguration($paths, true, null, null, false);
        $this->em = DoctrineEntityManager::create($connectionOptions, $config);
    }

    /**
     * @return EntityManager
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return DoctrineEntityManager
     */
    public function getManager()
    {
        return $this->em;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}