<?php
namespace CdliAutogenUsername\Filter;

use Zend\EventManager\EventManagerInterface;

abstract class AbstractFilter implements FilterInterface
{
    protected $events;

    public function __construct($options = NULL)
    {
        $this->setOptions($options);
        $this->init();
    }

    public function setEventManager(EventManagerInterface $em)
    {
        $this->events = $em;
    }
}

