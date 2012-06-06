<?php
namespace CdliAutogenUsername\Datasource;

interface DatasourceInterface
{
    public function findByUsername($username);
    public function setUsername($username);
}

