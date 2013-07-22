define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Select_storeManagers = require('blocks/select/select_storeManagers/select_storeManagers');

    return Block.extend({
        blockName: 'store__managers',
        storeManagersCollection: null,
        templates: {
            index: require('tpl!blocks/store/templates/store__managers.html')
        },
        events: {
            'change #select_storeManagers': function() {

            }
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            block.select_storeManagers = new Select_storeManagers({
                storeManagersCollection: block.storeManagersCollection,
                el: document.getElementById('select_storeManagers')
            });


        }
    });
});