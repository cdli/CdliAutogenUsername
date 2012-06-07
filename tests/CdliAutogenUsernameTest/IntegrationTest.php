<?php
namespace CdliAutogenUsernameTest;

use CdliAutogenUsername\Generator;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterChainsWithNoDatasourceLookup()
    {
        $gen = new Generator(array(
            'filters' => array(
                'digits' => array(
                    'filter' => 'RandomDigits',
                    'options' => array( 'digits' => 5 ),
                ),
                'prefix' => array(
                    'filter' => 'StaticString',
                    'options' => array( 'string' => 'TEST' ),
                ),
            ),
        ));
        $this->assertRegexp('/^[0-9]{5}TEST$/', $gen->generate());
    }

    public function testFilterChainsWithDatasourceLookupWhichNeverReturnsTrue()
    {
        $gen = new Generator(array(
            'datasource' => array(
                'plugin' => 'test'
            ),
            'filters' => array(
                'digits' => array(
                    'filter' => 'RandomDigits',
                    'options' => array( 'digits' => 5 ),
                ),
                'prefix' => array(
                    'filter' => 'StaticString',
                    'options' => array( 'string' => 'TEST' ),
                ),
            ),
        ));

        $mock = $this->getMock('CdliAutogenUsername\Datasource\DatasourceInterface');
        $mock->expects($this->any())->method('isUsernameTaken')->will($this->returnCallback(
            function($value) {
                return false;
            }
        ));
        $gen->getDatasourceBroker()->register('test', $mock);

        $this->assertRegexp('/^[0-9]{5}TEST$/', $gen->generate());
    }

    public function testFilterChainsWithDatasourceLookupWhichReturnsTrueOnTheThirdCheck()
    {
        $gen = new Generator(array(
            'datasource' => array(
                'plugin' => 'test'
            ),
            'filters' => array(
                'digits' => array(
                    'filter' => 'RandomDigits',
                    'options' => array( 'digits' => 5 ),
                ),
                'prefix' => array(
                    'filter' => 'StaticString',
                    'options' => array( 'string' => 'TEST' ),
                ),
            ),
        ));

        $counter = 0;
        $mock = $this->getMock('CdliAutogenUsername\Datasource\DatasourceInterface');
        $mock->expects($this->exactly(3))->method('isUsernameTaken')->will($this->returnCallback(
            function($value) use (&$counter) {
                return (++$counter<3);
            }
        ));
        $gen->getDatasourceBroker()->register('test', $mock);

        $this->assertRegexp('/^[0-9]{5}TEST$/', $gen->generate());
    }

}
