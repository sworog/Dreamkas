define(function(require) {
    //requirements
    var Page = require('kit/core/page'),
        JobsCollection = require('collections/jobs'),
        Jobs = require('blocks/jobs/jobs');

    return Page.extend({
        pageName: 'page_common_jobs',
        partials: {
            '#content': require('tpl!./templates/jobs.html')
        },
        initialize: function(){
            var page = this;

            page.jobsCollection = new JobsCollection();

            $.when(page.jobsCollection.fetch()).then(function(){
                page.render();

                new Jobs({
                    el: document.getElementById('jobs'),
                    jobsCollection: page.jobsCollection
                });
            });
        }
    });
});