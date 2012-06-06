<?php
namespace CdliAutogenUsername;

use Zend\Loader\PluginSpecBroker;

class DatasourceBroker extends PluginSpecBroker
{
   protected $defaultClassLoader = 'CdliAutogenUsername\DatasourceLoader';

    protected function validatePlugin($plugin)
    {
        if (!$plugin instanceof Datasource\DatasourceInterface) {
            throw new \RuntimeException(get_class($plugin).' is not a datasource plugin');
        }
        return true;
    }
}
