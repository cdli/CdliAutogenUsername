<?php
namespace CdliAutogenUsername;

use Zend\Loader\PluginClassLoader;

class FilterLoader extends PluginClassLoader
{
    protected $plugins = array(
        'randomdigits' => 'CdliAutogenUsername\Filter\RandomDigits',
    );
}
