<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2019 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Bundle\PimcoreBundle\DataHub\QueryType;

use CoreShop\Bundle\PimcoreBundle\DataHub\Type\SerializedDataType;
use Pimcore\Bundle\DataHubBundle\GraphQL\QueryFieldConfigGenerator\Input;
use Pimcore\Model\DataObject\ClassDefinition\Data;

class SerializedData extends Input
{
    /**
     * {@inheritdoc}
     */
    public function getGraphQlFieldConfig($attribute, Data $fieldDefinition, $class = null, $container = null)
    {
        return $this->enrichConfig(
            $fieldDefinition,
            $class,
            $attribute,
            [
            'name' => $fieldDefinition->getName(),
            'type' => $this->getFieldType($fieldDefinition, $class, $container),
        ],
            $container
            );
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldType(Data $fieldDefinition, $class = null, $container = null)
    {
        return new SerializedDataType();
    }
}
