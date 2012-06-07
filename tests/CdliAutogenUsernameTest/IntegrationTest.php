<?php
namespace CdliAutogenUsernameTest;

use CdliAutogenUsername\Generator;

class IntegrationTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleFilterChain()
    {
        $gen = new Generator(array(
            'datasource' => 'test',
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
        $gen->getDatasourceBroker()->register('test', $mock);

        var_dump($gen->generate());
    }
}
