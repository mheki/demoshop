<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Catalog\Model;

use Spryker\Client\Catalog\Model\FacetConfig as CoreFacetConfig;

class FacetConfig extends CoreFacetConfig
{

    /**
     * @var array
     */
    protected static $attributes = [
        'name' => [
            self::KEY_SORT_ACTIVE => false,
            self::KEY_SORT_FIELD_NAME => self::FIELD_STRING_SORT,
            self::KEY_FACET_ACTIVE => false,
            self::KEY_PARAM => 'name',
        ],
        'price' => [
            self::KEY_FACET_ACTIVE => true,
            self::KEY_SORT_ACTIVE => true,
            self::KEY_FACET_FIELD_NAME => self::FIELD_INTEGER_FACET,
            self::KEY_SORT_FIELD_NAME => self::FIELD_INTEGER_SORT,
            self::KEY_TYPE => self::TYPE_SLIDER,
            self::KEY_PARAM => 'price',
            self::KEY_RANGE_DIVIDER => '-',
            self::KEY_VALUE_CALLBACK_BEFORE => [__CLASS__, 'priceValueCallbackBefore'],
            self::KEY_VALUE_CALLBACK_AFTER => [__CLASS__, 'priceValueCallbackAfter'],
        ],
    ];

    /**
     * @var array
     */
    protected static $stringFacetFields = [
        self::FIELD_STRING_FACET,
    ];

    /**
     * @var array
     */
    protected static $numericFacetFields = [
        self::FIELD_FLOAT_FACET,
        self::FIELD_INTEGER_FACET,
    ];

    /**
     * @var array
     */
    protected static $sortNamesMapping = ['name'];

    /**
     * @param int $value
     *
     * @return mixed
     */
    public static function priceValueCallbackBefore($value)
    {
        return $value * 100;
    }

    /**
     * @param int $value
     *
     * @return mixed
     */
    public static function priceValueCallbackAfter($value)
    {
        return $value / 100;
    }

}
