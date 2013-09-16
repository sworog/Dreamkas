define(function(require) {
    //requirements
    var Block = require('kit/core/block');

    return Block.extend({
        __name__: 'jobs',
        jobsCollection: null,
        templates: {
            index: require('tpl!blocks/jobs/templates/index.html')
        }
    });
});