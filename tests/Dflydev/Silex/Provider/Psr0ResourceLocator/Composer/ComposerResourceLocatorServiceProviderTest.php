<?php

namespace Dflydev\Silex\Provider\Psr0ResourceLocator\Composer;

use Dflydev\Composer\Autoload\ClassLoaderReader;
use Dflydev\Silex\Provider\Psr0ResourceLocator\Psr0ResourceLocatorServiceProvider;
use Silex\Application;

/**
 * Psr0ResourceLocatorServiceProvider Test.
 *
 * @author Beau Simensen <beau@dflydev.com>
 */
class ComposerResourceLocatorServiceProviderTest extends \PHPUnit_Framework_TestCase
{
    public function testRegisterDoesNotCreateRootPsr0ResourceLocator()
    {
        $app = new Application;

        $app->register(new ComposerResourceLocatorServiceProvider);

        $this->assertTrue(empty($app['psr0_resource_locator']));
    }

    public function testRegisterPlain()
    {
        $app = new Application;

        $app->register(new Psr0ResourceLocatorServiceProvider);
        $app->register(new ComposerResourceLocatorServiceProvider);

        $this->assertInstanceOf('Dflydev\Psr0ResourceLocator\Composer\ComposerResourceLocator', $app['psr0_resource_locator']);
    }

    public function testRegisterPlainOutOfOrder()
    {
        $app = new Application;

        $app->register(new ComposerResourceLocatorServiceProvider);
        $app->register(new Psr0ResourceLocatorServiceProvider);

        $this->assertInstanceOf('Dflydev\Psr0ResourceLocator\Composer\ComposerResourceLocator', $app['psr0_resource_locator']);
    }

    public function testRegisterWithExistingImplementationSpecified()
    {
        $app = new Application;

        $app['psr0_resource_locator.implementation'] = 'some.other.service';
        $app['some.other.service'] = 'some other service';

        $app->register(new ComposerResourceLocatorServiceProvider);
        $app->register(new Psr0ResourceLocatorServiceProvider);

        $this->assertEquals('some other service', $app['psr0_resource_locator']);
    }

    public function testClassLoaderLocatorOverwrite()
    {
        $app = new Application;

        $classLoader = $this->getMock('Composer\Autoload\ClassLoader');

        $classLoader
            ->expects($this->once())
            ->method('getPrefixes')
            ->will($this->returnValue(array(
                'Dflydev\Silex\Provider\Psr0ResourceLocator\Composer' => array(realpath(__DIR__.'/../../../../..')),
            )));

        $classLoaderLocator = $this->getMock('Dflydev\Composer\Autoload\ClassLoaderLocator');

        $classLoaderLocator
            ->expects($this->once())
            ->method('locate')
            ->will($this->returnValue($classLoader));

        $classLoaderLocator
            ->expects($this->once())
            ->method('getReader')
            ->will($this->returnValue(
                new ClassLoaderReader($classLoader)
            ));

        $app['psr0_resource_locator_composer.class_loader_locator'] = $classLoaderLocator;

        $app->register(new Psr0ResourceLocatorServiceProvider);
        $app->register(new ComposerResourceLocatorServiceProvider);

        $directory = $app['psr0_resource_locator']->findFirstDirectory('Dflydev\Silex\Provider\Psr0ResourceLocator\Composer');
        $this->assertEquals(__DIR__, $directory);
    }
}
