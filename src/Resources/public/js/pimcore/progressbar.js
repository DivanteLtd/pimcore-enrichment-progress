/**
 * @category    enrichment-progress
 * @date        20/09/2018 13:46
 * @author      Korneliusz Kirsz <kkirsz@divante.co>
 * @copyright   Copyright (c) 2021 Divante Ltd. (https://divante.co/)
 */

pimcore.registerNS("pimcore.plugin.PimcoreEnrichmentBundle.ProgressBar");

pimcore.plugin.PimcoreEnrichmentBundle.ProgressBar = Class.create({
    
    initialize: function (object) {
        this.object = object;
        this.getProgressBar();
        this.refreshProgress();
    },
    
    refreshProgress: function () {
        Ext.Ajax.request({
            url: '/admin/enrichment/progress/' + this.object.id,
            success: function (response) {
                var data = Ext.decode(response.responseText);
                this.updateProgress(data.completed, data.total);
            }.bind(this)
        });        
    },
    
    updateProgress: function (completed, total) {
        completed = parseInt(completed);
        total = parseInt(total);
        var value = total > 0 ? (completed / total) : 1;
        var text = 'Enrichment progress: ' + completed + '/' + total;
        this.getProgressBar().updateProgress(value, text);        
    },    
    
    getProgressBar: function () {
        
        if (!this.progressBar) {
            this.progressBar = new Ext.ProgressBar({
                width: 300,
                border: 1,
                style: {
                    borderColor: '#000000',
                    borderStyle: 'solid'                
                }                
            });
            
            this.progressBar.on('destroy', function () {
                pimcore.globalmanager.remove('progressbar_' + this.object.id);
            }.bind(this));
            
            var toolbar = Ext.getCmp('object_toolbar_' + this.object.id);
            toolbar.add(this.progressBar);
        }
        
        return this.progressBar;
    }    
});
