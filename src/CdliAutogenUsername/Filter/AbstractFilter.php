<?php
namespace CdliAutogenUsername\Filter;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;

abstract class AbstractFilter implements FilterInterface, EventManagerAwareInterface
{
    protected $events;

    public function __construct($options = NULL)
    {
        $this->setOptions($options);
    }

    public function init()
    {
        $this->getEventManager()->attach('performAction', array($this, 'performAction'));
    }

    /**
     * Set the event manager instance used by this context
     * 
     * @param  EventManagerInterface $events 
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $this->events = $events;
        return $this;
    }

    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     * 
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        return $this->events;
    }

    protected function doPlacement($src, $add, $placement)
    {
        switch ($placement)
        {
            case 'prepend':
                $src = $add . $src;
                break;
            case 'append':
                $src .= $add;
                break;
            case 'replace':
                $src = $add;
                break;
        }
        return $src;
    }

}

