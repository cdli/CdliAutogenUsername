<?php

namespace CdliAutogenUsername;

use Zend\ModuleManager\ModuleManager,
    Zend\EventManager\StaticEventManager,
    Zend\ModuleManager\Feature\AutoloaderProviderInterface,
    Zend\ModuleManager\Feature\ServiceProviderInterface,
    Zend\ModuleManager\Feature\ConfigProviderInterface,
    Zend\EventManager\Event as Event;

class Module implements
    AutoloaderProviderInterface,
    ServiceProviderInterface,
    ConfigProviderInterface
{

    public function getServiceConfiguration()
    {
        return array(
            'invokables' => array(
                'CdliAutogenUsername\DatasourceBroker' => 'CdliAutogenUsername\DatasourceBroker',
            ),
            'factories' => array(
                'cdliautogenusername_module_options' => function($sm) {
                    $config = $sm->get('Configuration');
                    return new Options\ModuleOptions(isset($config['cdli-autogen-username'])
                        ? $config['cdli-autogen-username'] : array()
                    );
                },
                'CdliAutogenUsername\Generator' => function($sm) {
                    $obj = new Generator($sm->get('cdliautogenusername_module_options'));
                    $obj->setDatasourceBroker($sm->get('CdliAutogenUsername\DatasourceBroker'));
                    $obj->setServiceLocator($sm);
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
