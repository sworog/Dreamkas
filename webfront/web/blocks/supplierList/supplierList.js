define(function(require, exports, module) {
    //requirements
    var CollectionBlock = require('kit/collectionBlock/collectionBlock');

    return CollectionBlock.extend({
        template: require('ejs!./template.ejs'),
        collection: function(){
            return PAGE.get('collections.suppliers');
        }
    });
});