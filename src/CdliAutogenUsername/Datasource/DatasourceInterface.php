<?php
namespace CdliAutogenUsername\Datasource;
use Zend\ServiceManager\ServiceLocatorInterface;

interface DatasourceInterface
{
    public function init(ServiceLocatorInterface $sl = NULL);
    public function isUsernameTaken($username);
    public function setOptions($options);
}

