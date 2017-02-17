<?php

namespace ReddogsTest\Migrations\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Reddogs\Migrations\Helper\ModuleConfigurationHelper;
use Doctrine\DBAL\Migrations\Configuration\Configuration;

class LoadModuleConfigurationTraitTest extends TestCase
{
    private $loadModuleConfigurationTrait;

    protected function setUp()
    {
        $this->loadModuleConfigurationTrait = $this->getMockBuilder('Reddogs\Migrations\Command\LoadModuleConfigurationTrait')
            ->setMethods(['getHelperSet', 'setMigrationConfiguration'])
            ->getMockForTrait();
    }

    public function testLoadModuleConfiguration()
    {
        $helperSet = $this->getMockBuilder(HelperSet::class)
            ->setMethods(['get'])
            ->getMock();

        $this->loadModuleConfigurationTrait->expects($this->once())
            ->method('getHelperSet')
            ->will($this->returnValue($helperSet));

        $moduleConfiguratoinHelper = $this->getMockBuilder(ModuleConfigurationHelper::class)
            ->setMethods(['getModuleMigrationConfig'])
            ->disableOriginalConstructor()
            ->getMock();

        $helperSet->expects($this->once())
            ->method('get')
            ->with($this->equalTo('module-configuration'))
            ->will($this->returnValue($moduleConfiguratoinHelper));

        $configuration = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $moduleConfiguratoinHelper->expects($this->once())
            ->method('getModuleMigrationConfig')
            ->with($this->equalTo('testmodule'))
            ->will($this->returnValue($configuration));

        $this->loadModuleConfigurationTrait->expects($this->once())
            ->method('setMigrationConfiguration')
            ->with($this->identicalTo($configuration));

        $this->loadModuleConfigurationTrait->loadModuleConfiguration('testmodule');
    }
}