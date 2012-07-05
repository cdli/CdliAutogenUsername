<?php
namespace CdliAutogenUsername;

use Zend\ServiceManager\AbstractPluginManager;

class FilterBroker extends AbstractPluginManager
{
    protected $invokableClasses = array(
        'randomdigits' => 'CdliAutogenUsername\Filter\RandomDigits',
        'staticstring' => 'CdliAutogenUsername\Filter\StaticString',
    );

    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof Filter\FilterInterface) {
            throw new \RuntimeException(get_class($plugin).' is not a filter plugin');
        }
        return true;
    }
}
