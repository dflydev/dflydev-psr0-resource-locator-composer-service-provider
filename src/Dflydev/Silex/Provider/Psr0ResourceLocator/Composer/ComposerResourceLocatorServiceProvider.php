<?php

/*
 * This file is a part of dflydev/psr0-resource-locator-composer-service-provider.
 *
 * (c) Dragonfly Development Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dflydev\Silex\Provider\Psr0ResourceLocator\Composer;

use Dflydev\Psr0ResourceLocator\Composer\ComposerResourceLocator;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * PSR-0 Resource Locator Silex Service Provider.
 *
 * @author Beau Simensen <beau@dflydev.com>
 */
class ComposerResourceLocatorServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function boot(Application $app)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function register(Application $app)
    {
        if (empty($app['psr0_resource_locator.implementation'])) {
            $app['psr0_resource_locator.implementation'] = 'psr0_resource_locator_composer';
        }

        $app['psr0_resource_locator_composer'] = $app->share(function($app) {
            return new ComposerResourceLocator;
        });
    }
}
