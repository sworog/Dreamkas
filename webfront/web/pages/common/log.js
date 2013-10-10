define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        JobsCollection = require('collections/jobs'),
        LogCollection = require('collections/log'),
        Log = require('blocks/log/log');

    return Page.extend({
        __name__: 'page_common_jobs',
        partials: {
            '#content': require('tpl!./templates/log.html')
        },
        initialize: function(){
            var page = this;

            page.jobsCollection = new JobsCollection();
            page.logCollection = new LogCollection();

            $.when(page.jobsCollection.fetch(), page.logCollection.fetch()).then(function(){
                page.render();

                new Log({
                    el: document.getElementById('log'),
                    jobsCollection: page.jobsCollection,
                    logCollection: page.logCollection
                });
            });
        }
    });
});