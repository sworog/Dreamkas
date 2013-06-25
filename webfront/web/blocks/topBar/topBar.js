define(function(require) {
    //requirements
    var Block = require('kit/block'),
        app = require('app');

    var TopBar = Block.extend({
        className: 'topBar',
        blockName: 'topBar',
        templates: {
            index: require('tpl!./templates/topBar.html')
        },
        events: {
            'click .topBar__logoutLink': function(e){
                e.preventDefault();
                app.logout();
            }
        },
        initialize: function(){
            var block = this;

            block.$el.prependTo('body');

            Block.prototype.initialize.call(this);
        }
    });

    return new TopBar();
});