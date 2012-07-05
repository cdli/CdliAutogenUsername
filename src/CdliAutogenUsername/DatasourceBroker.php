<?php
namespace CdliAutogenUsername;

use Zend\ServiceManager\AbstractPluginManager;

class DatasourceBroker extends AbstractPluginManager
{
    /**
     * Default set of plugins
     *
     * @var array
     */
    protected $invokableClasses = array(
        'zfcuser' => 'CdliAutogenUsername\Datasource\ZfcUser', 
    );

    /**
     * Validate the plugin
     *
     * Any plugin is considered valid in this context.
     *
     * @param  mixed $plugin
     * @return true
     */
    public function validatePlugin($plugin)
    {
        if (!$plugin instanceof Datasource\DatasourceInterface) {
            throw new \RuntimeException(get_class($plugin).' is not a datasource plugin');
        }
        return true;
    }
}
