<?php
namespace CdliAutogenUsername;

use Module as modCAU;
use Zend\Loader\Broker;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerAwareInterface;

class Generator implements EventManagerAwareInterface
{
    protected $options;

    protected $filterBroker;

    protected $datasourceBroker;

    /**
     * @var EventManagerInterface
     */
    protected $events;

    public function __construct($options = array())
    {
        $this->options = $options;
    }

    public function generate()
    {
        $this->registerPlugins();

        // Load the datasource adapter if one was specified
        if (isset($this->options['datasource'])) {
            $datasource = $this->getDatasourceBroker()->load(
                $this->options['datasource']['plugin'],
                isset($this->options['datasource']['options'])
                    ? $this->options['datasource']['options'] 
                    : array()
            );
        }

        // Keep on rockin' till we find a username not currently in use
        // (only runs once if no datasource is configured to check against)
        do {
            // Run the filters
            $result = $this->events()->trigger('performAction', $this, array(
                'value' => ''
            ));
            $username = $result->last();
        } while (isset($datasource) && $datasource->isUsernameTaken($username));

        return $username;
    }

    protected function registerPlugins()
    {
        if ( count($this->options['filters']) )
        {
            $broker = $this->getFilterBroker();
            foreach ( $this->options['filters'] as $filter=>$options )
            {
                if (!$broker->isLoaded($filter))
                {
                    // Register the custom shortname (class alias)
                    $broker->getClassLoader()->registerPlugin(
                        $filter,
                        __NAMESPACE__ . '\\Filter\\' . $options['filter']
                    );
                    unset($options['filter']);

                    // Register the plugin and it's configuration
                    $plugin = $broker->load($filter, $options['options']);
                    $plugin->setEventManager($this->events())->init();
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
    public function events()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }
}
