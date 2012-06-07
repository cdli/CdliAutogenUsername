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
                'test' => array(
                    'filter' => 'RandomDigits',
                    'priority' => -100,
                    'options' => array( 'digits' => 5 )
                ),
            ),
        ));

        $mock = $this->getMock('CdliAutogenUsername\Datasource\DatasourceInterface');
        $gen->getDatasourceBroker()->register('test', $mock);

        var_dump($gen->generate());
    }
}
