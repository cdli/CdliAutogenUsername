<?php
namespace CdliAutogenUsername\Filter;

abstract class AbstractFilter implements FilterInterface
{
    public function __construct($options = NULL)
    {
        $this->setOptions($options);
        $this->init();
    }
}

