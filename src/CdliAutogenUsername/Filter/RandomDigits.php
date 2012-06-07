<?php
namespace CdliAutogenUsername\Filter;

use Zend\EventManager\EventInterface;

class RandomDigits extends AbstractFilter
{
    protected $digitCount = 6;
    protected $placement = 'append';

    public function setOptions($options)
    {
        if (is_array($options) || $options instanceof \Traversable)
        {
            foreach ($options as $key=>$value)
            {
                switch ($key)
                {
                    case 'digits':
                        $this->digitCount = (int)$value;
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

        // Generate a "random" number
        $randomVal = '';
        for ($i=0;$i<$this->digitCount;$i++) {
            $randomVal .= mt_rand(0,9);
        }

        switch ($this->placement)
        {
            case 'prepend':
                $current = $randomVal . $current;
                break;
            case 'append':
                $current .= $randomVal;
                break;
            case 'replace':
                $current = $randomVal;
                break;
        }
        return $current;
    }
}
