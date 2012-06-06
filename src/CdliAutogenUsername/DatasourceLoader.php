<?php
namespace CdliAutogenUsername;

use Zend\Loader\PluginClassLoader;

class DatasourceLoader extends PluginClassLoader
{
    protected $plugins = array(
        'zfcuser' => 'CdliAutogenUsername\Datasource\ZfcUser',
    );
}
