<?php
namespace CdliAutogenUsername;

use Zend\Loader\PluginSpecBroker;

class FilterBroker extends PluginSpecBroker
{
   protected $defaultClassLoader = 'Zend\Loader\PluginClassLoader';

    protected function validatePlugin($plugin)
    {
        if (!$plugin instanceof Filter\FilterInterface) {
            throw new \RuntimeException(get_class($plugin).' is not a filter plugin');
        }
        return true;
    }
}
