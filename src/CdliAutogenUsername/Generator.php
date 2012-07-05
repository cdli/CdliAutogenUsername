<?php
namespace CdliAutogenUsername;

use Zend\ServiceManager\AbstractPluginManager as Broker;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class Generator implements EventManagerAwareInterface, ServiceLocatorAwareInterface
{
    protected $options;

    protected $filterBroker;

    protected $datasourceBroker;

    protected $serviceLocator;

    /**
     * @var EventManagerInterface
     */
    protected $events;

    public function __construct(Options\ModuleOptions $options)
    {
        $this->options = $options;
    }

    public function generate()
    {
        $this->registerPlugins();

        // Load the datasource adapter if one was specified
        if ($datasourceOptions = $this->options->getDatasource()) {
            $datasource = $this->getDatasourceBroker()->get(
                $datasourceOptions['plugin'],
                isset($datasourceOptions['options']) ? $datasourceOptions['options'] : array()
            );
            $datasource->init($this->getServiceLocator());
        }

        // Keep on rockin' till we find a username not currently in use
        // (only runs once if no datasource is configured to check against)
        do {
            // Run the filters
            $result = $this->getEventManager()->trigger('performAction', $this, array('value' => ''));
            $username = $result->last();
        } while (isset($datasource) && $datasource->isUsernameTaken($username));

        return $username;
    }

    protected function registerPlugins()
    {
        if ( count($this->options->getFilters()) )
        {
            $broker = $this->getFilterBroker();
            foreach ( $this->options->getFilters() as $filter=>$options )
            {
                if ($broker->has($options['filter']))
                {
                    $plugin = $broker->get($options['filter'], $options['options']);
                    $plugin->setEventManager($this->getEventManager());
                    $plugin->init();
                }
            }
        }
    }

    public function setFilterBroker(Broker $loader)
    {
        $this->filterBroker = $loader;
        return $this;
    }

    public function getFilterBroker()
    {
        if ( is_null($this->filterBroker) )
        {
            $this->filterBroker = new FilterBroker();
        }
        return $this->filterBroker;
    }

    public function setDatasourceBroker(Broker $loader)
    {
        $this->datasourceBroker = $loader;
        return $this;
    }

    public function getDatasourceBroker()
    {
        if ( is_null($this->datasourceBroker) )
        {
            $this->datasourceBroker = new DatasourceBroker();
        }
        return $this->datasourceBroker;
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
    public function getEventManager()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }

    public function setServiceLocator(ServiceLocatorInterface $sl)
    {
        $this->serviceLocator = $sl;
        return $this;
    }

    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
