<?php
namespace CdliAutogenUsername\Datasource;

interface DatasourceInterface
{
    public function isUsernameTaken($username);
    public function setOptions($options);
}

