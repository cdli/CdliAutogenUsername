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
}
