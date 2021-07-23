/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

pimcore.registerNS("pimcore.object.tags.enrichmentProgress");

pimcore.object.tags.enrichmentProgress = Class.create(pimcore.object.tags.calculatedValue, {

    type: 'enrichmentProgress',

    getLayoutEdit: function () {

        this.component = new Ext.ProgressBar({
            componentCls: 'object_field',
            disabled: true,
            textTpl: this.fieldConfig.title + ': {percent:number("0")}%',
            width: this.fieldConfig.width ? this.fieldConfig.width : 350
        });

        var value = this.data ? this.data / 100 : 0;
        this.component.updateProgress(value);

        return this.component;
    },

    getGridColumnConfig: function (field) {
        return {
            text: ts(field.label),
            sortable: true,
            dataIndex: field.key,
            xtype: 'widgetcolumn',
            widget: {
                xtype: 'progressbarwidget',
                textTpl: '{percent:number("0")}%'
            }
        };
    },

    getGridColumnFilter: function (field) {
        return {
            type: 'numeric',
            dataIndex: field.key
        };
    },

    isDirty: function () {
        return false;
    }
});
