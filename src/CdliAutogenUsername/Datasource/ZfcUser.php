<?php
namespace CdliAutogenUsername\Datasource;

use ZfcUser\Service\User as UserService;

class ZfcUser implements DatasourceInterface
{
    protected $service;

    public function isUsernameTaken($username)
    {
        return is_object($this->getService()->getByUsername($username));
    }

    public function setOptions($options) {}

    public function setService(UserService $service)
    {
        $this->service = $service;
        return $this;
    }

    public function getService()
    {
        return $this->service;
    }
}
