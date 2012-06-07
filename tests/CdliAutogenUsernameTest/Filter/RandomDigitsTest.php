<?php
namespace CdliAutogenUsernameTest\Filter;

use CdliAutogenUsername\Filter\RandomDigits;
use Zend\EventManager\Event;

class RandomDigitsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerPlacements
     */
    public function testPlacements($placement, $matchRegexp)
    {
        $event = new Event();
        $event->setParams(array('value' => 'xxx'));

        $obj = new RandomDigits();
        $obj->setOptions(array('digits'=>5, 'placement'=>$placement));
        $result = $obj->performAction($event);

        $this->assertRegexp($matchRegexp, $result);
    }

    public function providerPlacements()
    {
        return array(
            array('prepend', '/^[0-9]{5}xxx$/'),
            array('append',  '/^xxx[0-9]{5}$/'),
            array('replace', '/^[0-9]{5}$/'),
        );
    }
}
