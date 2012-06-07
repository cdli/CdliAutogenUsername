<?php

namespace CdliAutogenUsername;

use Zend\ModuleManager\ModuleManager,
    Zend\EventManager\StaticEventManager,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\BootstrapListenerInterface,
    Zend\ModuleManager\Feature\ServiceProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\EventManager\Event as Event;

class Module implements
    BootstrapListenerInterface,
    AutoloaderProviderInterface,
    ServiceProviderInterface,
    ConfigProviderInterface
{
    protected static $options;

    public function init(ModuleManager $moduleManager)
    {
        $moduleManager->events()->attach('loadModules.post', array($this, 'modulesLoaded'));
    }

    public function onBootstrap(Event $e)
    {
        $serviceManager = $e->getTarget()->getServiceManager();
        $sharedEvents = \Zend\EventManager\StaticEventManager::getInstance();
    }

    public function getServiceConfiguration()
    {
        return array(
            'factories' => array(
                'CdliAutogenUsername\Generator' => function($sm) {
                    $obj = new Generator(Module::getOption()->toArray());
                    // We do this so that the SL gets injected properly
                    $obj->setDatasourceBroker($sm->get('CdliAutogenUsername\DatasourceBroker'));
                    return $obj;
                },
                'CdliAutogenUsername\Datasource\ZfcUser' => function($sm) {
                    $obj = new Datasource\ZfcUser();
                    $obj->setMapper($sm->get('zfcuser_user_mapper'));
                    return $obj;
                }
            )
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig($env = NULL)
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function modulesLoaded($e)
    {
        $config = $e->getConfigListener()->getMergedConfig();
        static::$options = $config['cdli-autogen-username'];
    }

    /**
     * @TODO: Come up with a better way of handling module settings/options
     */
    public static function getOption($option = NULL)
    {
        if (is_null($option)) {
            return static::$options;
        }
        if (!isset(static::$options[$option])) {
            return null;
        }
        return static::$options[$option];
    }
}
