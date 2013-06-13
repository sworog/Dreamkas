define(function(require) {
    //requirements
    var Content = require('kit/content/content');

    return new Content({
        el: document.getElementById('content_main'),
        blockName: 'content_main',
        addClass: 'content_main'
    });
});