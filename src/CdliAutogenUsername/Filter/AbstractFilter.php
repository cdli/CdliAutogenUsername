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
        $this->events()->attach('performAction', array($this, 'performAction'));
    }

    /**
     * Set the event manager instance used by this context
     * 
     * @param  EventManagerInterface $events 
     * @return mixed
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $identifiers = array(__CLASS__, get_class($this));
        if (isset($this->eventIdentifier)) {
            if ((is_string($this->eventIdentifier))
                || (is_array($this->eventIdentifier))
                || ($this->eventIdentifier instanceof Traversable)
            ) {
                $identifiers = array_unique(array_merge($identifiers, (array) $this->eventIdentifier));
            } elseif (is_object($this->eventIdentifier)) {
                $identifiers[] = $this->eventIdentifier;
            }
            // silently ignore invalid eventIdentifier types
        }
        $events->setIdentifiers($identifiers);
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
    public function events()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
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

