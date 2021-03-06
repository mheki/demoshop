<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Wishlist;

use Spryker\Zed\ItemGrouperWishlistConnector\Communication\Plugin\PreSaveItemGroupingPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductOptionWishlistConnector\Communication\Plugin\PreSaveGroupKeyProductOptionPlugin;
use Spryker\Zed\Wishlist\Business\Operator\Add;
use Spryker\Zed\Wishlist\Business\Operator\Decrease;
use Spryker\Zed\Wishlist\Business\Operator\Increase;
use Spryker\Zed\Wishlist\Business\Operator\Remove;
use Spryker\Zed\Wishlist\Communication\Plugin\PreSaveSkuGroupKeyPlugin;
use Spryker\Zed\Wishlist\WishlistDependencyProvider as SprykerWishlistDependencyProvider;

class WishlistDependencyProvider extends SprykerWishlistDependencyProvider
{

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return array
     */
    protected function preSavePlugins(Container $container)
    {
        $groupingPlugin = new PreSaveItemGroupingPlugin();

        return [
            Add::OPERATION_NAME => [
                new PreSaveSkuGroupKeyPlugin(),
                new PreSaveGroupKeyProductOptionPlugin(),
                $groupingPlugin,
            ],
            Decrease::OPERATION_NAME => [
                $groupingPlugin,
            ],
            Increase::OPERATION_NAME => [
                $groupingPlugin,
            ],
            Remove::OPERATION_NAME => [
                $groupingPlugin,
            ],
        ];
    }

}
