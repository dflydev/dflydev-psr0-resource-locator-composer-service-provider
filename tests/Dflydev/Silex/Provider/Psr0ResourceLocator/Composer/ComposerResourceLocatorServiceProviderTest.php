<?php

namespace Dflydev\Silex\Provider\Psr0ResourceLocator\Composer;

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
}
