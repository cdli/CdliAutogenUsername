<?php
namespace CdliAutogenUsername\Filter;

use Zend\EventManager\EventInterface;

interface FilterInterface
{
    public function init();
    public function setOptions($options);
    public function performAction(EventInterface $e);
}

