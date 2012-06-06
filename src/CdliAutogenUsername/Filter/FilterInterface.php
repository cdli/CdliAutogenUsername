<?php
namespace CdliAutogenUsername\Filter;

interface FilterInterface
{
    public function setOptions();
    public function generate();
}

