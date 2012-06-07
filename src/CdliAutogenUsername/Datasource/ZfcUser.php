<?php
namespace CdliAutogenUsername\Datasource;

use ZfcUser\Model\UserMapper;

class ZfcUser implements DatasourceInterface
{
    protected $mapper;

    public function isUsernameTaken($username)
    {
        return is_object($this->getMapper()->findByUsername($username));
    }

    public function setOptions($options) {}

    public function setMapper(UserMapper $mapper)
    {
        $this->mapper = $mapper;
        return $this;
    }

    public function getMapper()
    {
        return $this->mapper;
    }
}
