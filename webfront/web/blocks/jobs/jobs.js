define(function(require) {
    //requirements
    var Block = require('kit/core/block');

    return Block.extend({
        __name__: 'jobs',
        jobsCollection: null,
        template: require('tpl!blocks/jobs/templates/index.html')
    });
});