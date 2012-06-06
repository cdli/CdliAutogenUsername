<?php
namespace CdliAutogenUsernameTest;

use CdliAutogenUsername\Generator;
use CdliAutogenUsername\Datasource\DatasourceInterface;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->generator = new Generator();

        $mockDatasource = $this->getMock('CdliAutogenUsername\Datasource\DatasourceInterface');
        $mockDatasource->expects($this->any())
                       ->method('findByUsername')
                       ->will($this->returnValue(NULL));
        $mockDatasource->expects($this->any())
                       ->method('setUsername')
                       ->will($this->returnSelf());
        $dsb = $this->generator->getDatasourceBroker();
        $dsb->register('test', $mockDatasource);
    }

    public function testSetFilterBrokerAcceptsAnyLazyLoadingBroker()
    {
        $mock = $this->getMock('Zend\Loader\LazyLoadingBroker');
        $this->generator->setFilterBroker($mock);
        $this->assertEquals($mock, $this->generator->getFilterBroker());
    }

    public function testSetDatasourceBrokerAcceptsAnyLazyLoadingBroker()
    {
        $mock = $this->getMock('Zend\Loader\LazyLoadingBroker');
        $this->generator->setDatasourceBroker($mock);
        $this->assertEquals($mock, $this->generator->getDatasourceBroker());
    }
}
