<?php
namespace CdliAutogenUsernameTest\Filter;

use CdliAutogenUsername\Filter\StaticString;
use Zend\EventManager\Event;

class StaticStringTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providerPlacements
     */
    public function testPlacements($placement, $matchRegexp)
    {
        $event = new Event();
        $event->setParams(array('value' => 'xxx'));

        $obj = new StaticString();
        $obj->setOptions(array('string'=>'DDD', 'placement'=>$placement));
        $result = $obj->performAction($event);

        $this->assertRegexp($matchRegexp, $result);
    }

    public function providerPlacements()
    {
        return array(
            array('prepend', '/^DDDxxx$/'),
            array('append',  '/^xxxDDD$/'),
            array('replace', '/^DDD$/'),
        );
    }
}
