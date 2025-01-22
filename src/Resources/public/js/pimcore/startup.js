/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2021 Divante Ltd. (https://divante.co/)
 */

pimcore.registerNS("pimcore.plugin.PimcoreEnrichmentBundle");

pimcore.plugin.PimcoreEnrichmentBundle = Class.create({
    getClassName: function () {
        return "pimcore.plugin.PimcoreEnrichmentBundle";
    },

    initialize: function () {
        document.addEventListener(pimcore.events.postOpenObject, this.postOpenObject.bind(this));
        document.addEventListener(pimcore.events.postSaveObject, this.postSaveObject.bind(this));
    },

    pimcoreReady: function (params, broker) {
    },
    
    postOpenObject: function (event) {
        const object = event.detail.object;

        if (this.showProgress(object)) {
            const key = 'progressbar_' + object.id;
            const value = new pimcore.plugin.PimcoreEnrichmentBundle.ProgressBar(object);
            pimcore.globalmanager.add(key, value);
        }
    },
    
    postSaveObject: function (event) {
        const object = event.detail.object;

        if (this.showProgress(object)) {
            const key = 'progressbar_' + object.id;
            pimcore.globalmanager.get(key).refreshProgress();
        }
    },

    showProgress: function (object) {
        return object.preview !== undefined;
    }
});

var PimcoreEnrichmentBundlePlugin = new pimcore.plugin.PimcoreEnrichmentBundle();
