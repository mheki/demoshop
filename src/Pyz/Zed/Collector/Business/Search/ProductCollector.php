<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Collector\Business\Search;

use Generated\Shared\Transfer\LocaleTransfer;
use Pyz\Zed\Collector\CollectorConfig;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Zed\Collector\Business\Collector\Search\AbstractSearchPdoCollector;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet;
use Spryker\Zed\Price\Business\PriceFacadeInterface;
use Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface;

class ProductCollector extends AbstractSearchPdoCollector
{

    /**
     * @var \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface
     */
    protected $productSearchFacade;

    /**
     * @var \Spryker\Zed\Price\Business\PriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\ProductSearch\Business\ProductSearchFacadeInterface $productSearchFacade
     * @param \Spryker\Zed\Price\Business\PriceFacadeInterface $priceFacade
     */
    public function __construct(
        ProductSearchFacadeInterface $productSearchFacade,
        PriceFacadeInterface $priceFacade
    ) {
        $this->productSearchFacade = $productSearchFacade;
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    protected function collectItem($touchKey, array $collectItemData)
    {
        $collectItemData['price'] = $this->getPriceBySku($collectItemData['abstract_sku']);

        return $collectItemData;
    }

    /**
     * @param string $sku
     *
     * @return int
     */
    protected function getPriceBySku($sku)
    {
        return $this->priceFacade->getPriceBySku($sku);
    }

    /**
     * @return string
     */
    protected function collectResourceType()
    {
        return ProductConstants::RESOURCE_TYPE_PRODUCT_ABSTRACT;
    }

    /**
     * @param array $collectedSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     *
     * @return array
     */
    protected function collectData(array $collectedSet, LocaleTransfer $locale, TouchUpdaterSet $touchUpdaterSet)
    {
        $collectedSet = parent::collectData($collectedSet, $locale, $touchUpdaterSet);

        return $this->processData($collectedSet, $locale);
    }

    /**
     * @param array $resultSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    protected function processData($resultSet, LocaleTransfer $locale)
    {
        $processedResultSet = $this->buildProducts($resultSet, $locale);

        $processedResultSet = $this->productSearchFacade->enrichProductsWithSearchAttributes(
            $resultSet,
            $processedResultSet
        );

        foreach ($resultSet as $index => $productRawData) {
            if (!isset($processedResultSet[$index])) {
                continue;
            }

            $processedResultSet = $this->processAvailability($productRawData, $processedResultSet, $index);
            $processedResultSet = $this->processCategory($productRawData, $processedResultSet, $index);

            $processedResultSet[$index][CollectorConfig::COLLECTOR_TOUCH_ID] = $productRawData[CollectorConfig::COLLECTOR_TOUCH_ID];
            $processedResultSet[$index][CollectorConfig::COLLECTOR_RESOURCE_ID] = $productRawData[CollectorConfig::COLLECTOR_RESOURCE_ID];
            $processedResultSet[$index][CollectorConfig::COLLECTOR_SEARCH_KEY] = $productRawData[CollectorConfig::COLLECTOR_SEARCH_KEY];
        }

        return $processedResultSet;
    }

    /**
     * @param array $resultSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return array
     */
    protected function buildProducts(array &$resultSet, $locale)
    {
        $processedResultSet = [];

        $processedResultSet = $this->productSearchFacade->createSearchProducts($resultSet, $processedResultSet, $locale);

        $keys = array_keys($processedResultSet);
        $resultSet = array_combine($keys, $resultSet);

        return $processedResultSet;
    }

    /**
     * @param array $productRawData
     * @param array $processedResultSet
     * @param string $index
     *
     * @return array
     */
    protected function processAvailability(array $productRawData, array $processedResultSet, $index)
    {
        $processedResultSet[$index]['available'] = $productRawData['quantity'] > 0;
        $isAvailable = (bool)(
            $productRawData['is_never_out_of_stock'] ||
            $productRawData['quantity'] > 0
        );
        $processedResultSet[$index]['search-result-data']['available'] = $isAvailable;
        $processedResultSet[$index]['bool-facet']['available'] = $isAvailable;

        return $processedResultSet;
    }

    /**
     * @param array $productRawData
     * @param array $processedResultSet
     * @param string $index
     *
     * @return array
     */
    protected function processCategory(array $productRawData, array $processedResultSet, $index)
    {
        $processedResultSet[$index]['category'] = [
            'direct-parents' => explode(',', $productRawData['node_id']),
            'all-parents' => explode(',', $productRawData['category_parent_ids']),
        ];

        return $processedResultSet;
    }

}
