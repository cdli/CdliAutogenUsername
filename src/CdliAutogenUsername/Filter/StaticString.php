<?php
namespace CdliAutogenUsername\Filter;

use Zend\EventManager\EventInterface;

class StaticString implements FilterInterface
{
    protected $string = '';
    protected $placement = 'append';

    public function setOptions($options)
    {
        if (is_array($options) || $options instanceof \Traversable)
        {
            foreach ($options as $key=>$value)
            {
                switch ($key)
                {
                    case 'string':
                        $this->string = $value;
                        break;
                    case 'placement':
                        if (in_array($value, array('prepend','append','replace'))) {
                            $this->placement = $value;
                        }
                        break;
                }
            }
        }
    }

    public function performAction(EventInterface $event)
    {
        $current = $event->getParam('value');
        switch ($this->placement)
        {
            case 'prepend':
                $current = $this->string . $current;
                break;
            case 'append':
                $current .= $this->string;
                break;
            case 'replace':
                $current = $this->string;
                break;
        }
        return $current;
    }
}
