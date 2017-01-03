/**
 * CoreShop
 *
 * LICENSE
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

pimcore.registerNS('pimcore.plugin.coreshop.indexes.getters.fieldcollection');

pimcore.plugin.coreshop.indexes.getters.fieldcollection = Class.create(pimcore.plugin.coreshop.indexes.getters.abstract, {

    getLayout : function (record) {
        return [
            {
                xtype : 'textfield',
                fieldLabel : t('coreshop_index_field_collectionfield'),
                name : 'collectionField',
                length : 255,
                value : record.data.getterConfig ? record.data.getterConfig.collectionField : null
            }
        ];
    }

});
