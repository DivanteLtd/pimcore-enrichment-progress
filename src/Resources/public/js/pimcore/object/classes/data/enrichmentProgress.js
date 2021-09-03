/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

pimcore.registerNS('pimcore.object.classes.data.enrichmentProgress');

pimcore.object.classes.data.enrichmentProgress = Class.create(pimcore.object.classes.data.calculatedValue, {

    type: 'enrichmentProgress',

    initialize: function (treeNode, initData) {
        this.type = 'enrichmentProgress';
        this.initData(initData);
        this.treeNode = treeNode;
    },

    getTypeName: function () {
        return t('enrichmentProgress_field');
    },

    getLayout: function ($super) {

        $super();

        this.specificPanel.removeAll();
        this.specificPanel.add([
            {
                xtype: 'numberfield',
                fieldLabel: t('width'),
                name: 'width',
                value: this.datax.width,
                labelWidth: 140
            },
            {
                xtype: "numberfield",
                fieldLabel: t("columnlength"),
                name: "columnLength",
                value: this.datax.columnLength,
                labelWidth: 140
            }
        ]);

        return this.layout;
    },

    applySpecialData: function(source) {
    }
});
