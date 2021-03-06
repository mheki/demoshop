<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Payment;

use Spryker\Shared\Payolution\PayolutionConstants;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Payment\PaymentDependencyProvider as SprykerPaymentDependencyProvider;
use Spryker\Zed\Payolution\Communication\Plugin\Checkout\PayolutionPreCheckPlugin;
use Spryker\Zed\Payolution\Communication\Plugin\Checkout\PayolutionSaveOrderPlugin;

class PaymentDependencyProvider extends SprykerPaymentDependencyProvider
{

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     * @return array
     */
    protected function getCheckoutPlugins(Container $container)
    {
        return [
            self::CHECKOUT_PRE_CHECK_PLUGINS => [
                PayolutionConstants::PAYOLUTION => new PayolutionPreCheckPlugin(),
            ],
            self::CHECKOUT_ORDER_SAVER_PLUGINS => [
                PayolutionConstants::PAYOLUTION => new PayolutionSaveOrderPlugin(),
            ],
            self::CHECKOUT_POST_SAVE_PLUGINS => [],
        ];
    }

}
