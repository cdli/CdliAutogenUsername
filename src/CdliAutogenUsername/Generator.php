<?php
namespace CdliAutogenUsername;

use Module as modCAU;
use Zend\Loader\LazyLoadingBroker;

class Generator
{
    protected $filterBroker;

    protected $datasourceBroker;

    public function generate()
    {
    }

    public function setFilterBroker(LazyLoadingBroker $loader)
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

    public function setDatasourceBroker(LazyLoadingBroker $loader)
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
}
