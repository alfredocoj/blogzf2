<?php

namespace BlogParte1\Model;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\DBAL\DriverManager;

class BaseModel  implements ServiceLocatorAwareInterface
{
	 protected $services;

    protected function getDbalConnection()
    {
        $config = new \Doctrine\DBAL\Configuration;
        $params = $this->getServiceLocator()->get('Config');
        $params = $params['doctrine']['connection']['orm_default']['params'];

        return DriverManager::getConnection($params, $config);
    }


    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->services = $serviceLocator;
    }

    public function getServiceLocator()
    {
        return $this->services;
    }

    /**
     * Returns a Service
     *
     * @param  string $service
     * @return Service
     */
    protected function getService($service)
    {
        return $this->get($service);
    }
}