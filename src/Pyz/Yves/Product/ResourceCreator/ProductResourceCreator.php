<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Product\ResourceCreator;

use Pyz\Yves\Collector\Creator\AbstractResourceCreator;
use Pyz\Yves\Product\Builder\FrontendProductBuilderInterface;
use Silex\Application;
use Spryker\Shared\Kernel\LocatorLocatorInterface;
use Spryker\Yves\Kernel\BundleControllerAction;
use Spryker\Yves\Kernel\Controller\BundleControllerActionRouteNameResolver;

class ProductResourceCreator extends AbstractResourceCreator
{

    /**
     * @var \Pyz\Yves\Product\Builder\FrontendProductBuilderInterface
     */
    protected $productBuilder;

    /**
     * @var \Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    protected $locator;

    /**
     * @param \Pyz\Yves\Product\Builder\FrontendProductBuilderInterface $productBuilder
     * @param \Spryker\Shared\Kernel\LocatorLocatorInterface $locator
     */
    public function __construct(
        FrontendProductBuilderInterface $productBuilder,
        LocatorLocatorInterface $locator
    ) {
        $this->productBuilder = $productBuilder;
        $this->locator = $locator;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'product_abstract';
    }

    /**
     * @param \Silex\Application $application
     * @param array $data
     *
     * @return array
     */
    public function createResource(Application $application, array $data)
    {
        $bundleControllerAction = new BundleControllerAction('Product', 'Product', 'detail');
        $routeResolver = new BundleControllerActionRouteNameResolver($bundleControllerAction);

        $service = $this->createServiceForController($application, $bundleControllerAction, $routeResolver);
        $product = $this->productBuilder->buildProduct($data);

        return [
            '_controller' => $service,
            '_route' => $routeResolver->resolve(),
            'product' => $product,
        ];
    }

}
