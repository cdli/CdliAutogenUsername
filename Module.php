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
}
