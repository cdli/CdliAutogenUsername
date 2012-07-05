<?php

namespace CdliAutogenUsername\Options;

use Zend\Stdlib\AbstractOptions;

class ModuleOptions extends AbstractOptions
{
    protected $enabled = false;

    protected $datasource = array();

    protected $filters = array();


    public function setEnabled($en)
    {
        $this->enabled = ($en==true);
        return $this;
    }

    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setDatasource($ds)
    {
        $this->datasource = $ds;
        return $this;
    }

    public function getDatasource()
    {
        return $this->datasource;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    public function getFilters()
    {
        return $this->filters;
    }
}
