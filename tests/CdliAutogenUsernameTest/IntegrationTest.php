<?php
namespace CdliAutogenUsernameTest;

use CdliAutogenUsername\Generator;
use CdliAutogenUsername\Options\ModuleOptions;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testFilterChainsWithNoDatasourceLookup()
    {
        $options = new ModuleOptions(array(
            'filters' => array(
                'digits' => array(
                    'filter' => 'randomdigits',
                    'options' => array( 'digits' => 5 ),
                ),
                'prefix' => array(
                    'filter' => 'staticstring',
                    'options' => array( 'string' => 'TEST' ),
                ),
            ),
        ));
        $gen = new Generator($options);
        $this->assertRegexp('/^[0-9]{5}TEST$/', $gen->generate());
    }

    public function testFilterChainsWithDatasourceLookupWhichNeverReturnsTrue()
    {
        $options = new ModuleOptions(array(
            'datasource' => array(
                'plugin' => 'test'
            ),
            'filters' => array(
                'digits' => array(
                    'filter' => 'randomdigits',
                    'options' => array( 'digits' => 5 ),
                ),
                'prefix' => array(
                    'filter' => 'staticstring',
                    'options' => array( 'string' => 'TEST' ),
                ),
            ),
        ));
        $gen = new Generator($options);

        $mock = $this->getMock('CdliAutogenUsername\Datasource\DatasourceInterface');
        $mock->expects($this->any())->method('isUsernameTaken')->will($this->returnCallback(
            function($value) {
                return false;
            }
        ));
        $gen->getDatasourceBroker()->setService('test', $mock);

        $this->assertRegexp('/^[0-9]{5}TEST$/', $gen->generate());
    }

    public function testFilterChainsWithDatasourceLookupWhichReturnsTrueOnTheThirdCheck()
    {
        $options = new ModuleOptions(array(
            'datasource' => array(
                'plugin' => 'test'
            ),
            'filters' => array(
                'digits' => array(
                    'filter' => 'randomdigits',
                    'options' => array( 'digits' => 5 ),
                ),
                'prefix' => array(
                    'filter' => 'staticstring',
                    'options' => array( 'string' => 'TEST' ),
                ),
            ),
        ));
        $gen = new Generator($options);

        $counter = 0;
        $mock = $this->getMock('CdliAutogenUsername\Datasource\DatasourceInterface');
        $mock->expects($this->exactly(3))->method('isUsernameTaken')->will($this->returnCallback(
            function($value) use (&$counter) {
                return (++$counter<3);
            }
        ));
        $gen->getDatasourceBroker()->setService('test', $mock);

        $this->assertRegexp('/^[0-9]{5}TEST$/', $gen->generate());
    }

}
