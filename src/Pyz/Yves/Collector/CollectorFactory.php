<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Collector;

use Pyz\Yves\Collector\Mapper\ParameterMerger;
use Pyz\Yves\Collector\Mapper\RequestParameterInjector;
use Pyz\Yves\Collector\Mapper\UrlMapper;
use Spryker\Yves\Kernel\AbstractFactory;

class CollectorFactory extends AbstractFactory
{

    /**
     * @return \Pyz\Yves\Collector\Creator\ResourceCreatorInterface[]
     */
    public function createResourceCreators()
    {
        return [
            $this->createProductResourceCreator(),
            $this->createCategoryResourceCreator(),
            $this->createRedirectResourceCreator(),
            $this->createPageResourceCreator(),
        ];
    }

    /**
     * @return \Pyz\Yves\Collector\Mapper\UrlMapperInterface
     */
    public function createUrlMapper()
    {
        return new UrlMapper($this->createFacetConfig());
    }

    /**
     * @return \Pyz\Yves\Collector\Mapper\ParameterMergerInterface
     */
    public function createParameterMerger()
    {
        return new ParameterMerger($this->createFacetConfig());
    }

    /**
     * @return \Pyz\Yves\Collector\Mapper\RequestParameterInjectorInterface
     */
    public function createRequestParameterInjector()
    {
        return new RequestParameterInjector($this->createFacetConfig());
    }

    /**
     * @return \Spryker\Client\Collector\Matcher\UrlMatcher
     */
    public function createUrlMatcher()
    {
        $urlMatcher = $this->getProvidedDependency(CollectorDependencyProvider::CLIENT_COLLECTOR);

        return $urlMatcher;
    }

    /**
     * @return \Spryker\Client\Catalog\Model\FacetConfig
     */
    protected function createFacetConfig()
    {
        $catalogClient = $this->getProvidedDependency(CollectorDependencyProvider::CLIENT_CATALOG);

        return $catalogClient->createFacetConfig();
    }

    /**
     * @return \Silex\Application
     */
    public function createApplication()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Pyz\Yves\Product\Plugin\ProductResourceCreator
     */
    protected function createProductResourceCreator()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::PLUGIN_PRODUCT_RESOURCE_CREATOR);
    }

    /**
     * @return \Pyz\Yves\Category\Plugin\CategoryResourceCreator
     */
    protected function createCategoryResourceCreator()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::PLUGIN_CATEGORY_RESOURCE_CREATOR);
    }

    /**
     * @return \Pyz\Yves\Redirect\Plugin\RedirectResourceCreator
     */
    protected function createRedirectResourceCreator()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::PLUGIN_REDIRECT_RESOURCE_CREATOR);
    }

    /**
     * @return \Pyz\Yves\Cms\Plugin\PageResourceCreator
     */
    protected function createPageResourceCreator()
    {
        return $this->getProvidedDependency(CollectorDependencyProvider::PLUGIN_PAGE_RESOURCE_CREATOR);
    }

}
