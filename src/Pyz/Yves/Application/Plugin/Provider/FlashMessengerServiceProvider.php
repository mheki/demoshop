<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Application\Plugin\Provider;

use Pyz\Yves\Application\Business\Model\FlashMessenger;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

class FlashMessengerServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['flash_messenger'] = function ($app) {
            return $this->createFlashMessenger($app);
        };
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return \Pyz\Yves\Application\Business\Model\FlashMessenger
     */
    protected function createFlashMessenger(Application $app)
    {
        return new FlashMessenger($app['session']->getFlashBag());
    }

}
