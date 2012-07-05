<?php
namespace CdliAutogenUsernameTest;

use CdliAutogenUsername\Generator;
use CdliAutogenUsername\Datasource\DatasourceInterface;
use CdliAutogenUsername\Options\ModuleOptions;

class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->generator = new Generator(new ModuleOptions());

        $mockDatasource = $this->getMock('CdliAutogenUsername\Datasource\DatasourceInterface');
        $mockDatasource->expects($this->any())
                       ->method('findByUsername')
                       ->will($this->returnValue(NULL));
        $mockDatasource->expects($this->any())
                       ->method('setUsername')
                       ->will($this->returnSelf());
        $dsb = $this->generator->getDatasourceBroker();
        $dsb->setService('test', $mockDatasource);
    }

    public function testSetFilterBrokerAcceptsAnyLazyLoadingBroker()
    {
        $mock = $this->getMock('Zend\ServiceManager\AbstractPluginManager');
        $this->generator->setFilterBroker($mock);
        $this->assertEquals($mock, $this->generator->getFilterBroker());
    }

    public function testSetDatasourceBrokerAcceptsAnyLazyLoadingBroker()
    {
        $mock = $this->getMock('Zend\ServiceManager\AbstractPluginManager');
        $this->generator->setDatasourceBroker($mock);
        $this->assertEquals($mock, $this->generator->getDatasourceBroker());
    }
}
