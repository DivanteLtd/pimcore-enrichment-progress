/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2021 Divante Ltd. (https://divante.co/)
 */

pimcore.registerNS("pimcore.plugin.PimcoreEnrichmentBundle");

pimcore.plugin.PimcoreEnrichmentBundle = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return "pimcore.plugin.PimcoreEnrichmentBundle";
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    pimcoreReady: function (params, broker) {
    },
    
    postOpenObject: function (object, type) {
        if (this.hasWorkflow(object)) {
            var key = 'progressbar_' + object.id;
            var value = new pimcore.plugin.PimcoreEnrichmentBundle.ProgressBar(object);
            pimcore.globalmanager.add(key, value);
        }
    },
    
    postSaveObject: function (object) {
        if (this.hasWorkflow(object)) {
            var key = 'progressbar_' + object.id;
            pimcore.globalmanager.get(key).refreshProgress();
        }
    },

    hasWorkflow: function (object) {
        return true;
    }
});

var PimcoreEnrichmentBundlePlugin = new pimcore.plugin.PimcoreEnrichmentBundle();
