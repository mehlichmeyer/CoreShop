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

pimcore.registerNS('pimcore.plugin.coreshop.mail.rules.conditions.payment');

pimcore.plugin.coreshop.mail.rules.conditions.payment = Class.create(pimcore.plugin.coreshop.rules.conditions.abstract, {
    type : 'payment',

    getForm : function () {
        var paymentProvidersStore = new Ext.data.Store({
            proxy : {
                type : 'ajax',
                url : '/plugin/CoreShop/admin_order/get-payment-providers',
                reader : {
                    type : 'json',
                    rootProperty : 'data'
                }
            },
            fields : ['id', 'name']
        });
        paymentProvidersStore.load();

        var providers = new Ext.ux.form.MultiSelect({
            typeAhead: true,
            listWidth: 100,
            width : 500,
            forceSelection: true,
            maxHeight : 400,
            delimiter : false,
            labelWidth : 150,
            fieldLabel:t('coreshop_paymentProvider'),
            mode:'local',
            store:paymentProvidersStore,
            displayField:'name',
            valueField:'id',
            triggerAction:'all',
            name:'providers',
            multiSelect: true,
            value : this.data ? this.data.providers : []
        });

        this.form = Ext.create('Ext.form.FieldSet', {
            items : [
                providers
            ]
        });

        return this.form;
    }
});
