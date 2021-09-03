/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2018 Divante Ltd. (https://divante.co/)
 */

pimcore.registerNS("pimcore.object.tags.enrichmentProgress");

pimcore.object.tags.enrichmentProgress = Class.create(pimcore.object.tags.calculatedValue, {

    type: 'enrichmentProgress',

    getGridColumnConfig: function (field) {
        var renderer = function (key, value, metaData, record) {
            this.applyPermissionStyle(key, value, metaData, record);

            try {
                if (record.data.inheritedFields && record.data.inheritedFields[key] && record.data.inheritedFields[key].inherited == true) {
                    metaData.tdCls += " grid_value_inherited";
                }
            } catch (e) {
                console.log(e);
            }
            return value;

        }.bind(this, field.key);

        return {
            header: ts(field.label),
            sortable: true,
            dataIndex: field.key,
            renderer: renderer,
            xtype: 'widgetcolumn',
            widget: {
                xtype: 'progressbarwidget',
                textTpl: [
                    '{percent:number("0")}%'
                ]
            },
            editor: this.getGridColumnEditor(field)
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
