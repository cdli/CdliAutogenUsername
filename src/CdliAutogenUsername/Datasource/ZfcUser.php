<?php
namespace CdliAutogenUsername\Datasource;

use ZfcUser\Mapper\UserInterface as UserMapper;
use Zend\ServiceManager\ServiceLocatorInterface;

class ZfcUser implements DatasourceInterface
{
    protected $mapper;

    public function init(ServiceLocatorInterface $sl = NULL)
    {
        if ( ! $sl instanceof ServiceLocatorInterface ) {
            throw new \InvalidArgumentException('Datasource "ZfcUser" requires instance of Service Locator');
        }
        $this->setMapper($sl->get('zfcuser_user_mapper'));
    }

    public function isUsernameTaken($username)
    {
        return is_object($this->getMapper()->findByUsername($username));
    }

    public function setOptions($options) {}

    public function setMapper(UserMapper $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        return $this->mapper;
    }
}
