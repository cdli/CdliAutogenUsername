<?php
namespace CdliAutogenUsername\Filter;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

interface FilterInterface
{
    public function init();
    public function setOptions($options);
    public function performAction(EventInterface $e);
    public function setEventManager(EventManagerInterface $em);
}

