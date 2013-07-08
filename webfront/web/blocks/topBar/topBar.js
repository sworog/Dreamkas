define(function(require) {
    //requirements
    var Block = require('kit/block'),
        app = require('app'),
        currentUserModel = require('models/currentUser'),
        userPermissions = require('models/userPermissions');

    var TopBar = Block.extend({
        className: 'topBar',
        blockName: 'topBar',
        currentUserModel: currentUserModel,
        templates: {
            index: require('tpl!blocks/topBar/templates/index.html')
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

            block.listenTo(currentUserModel, {
                change: function(){
                    block.render();
                }
            });

            block.listenTo(userPermissions, {
                change: function(){
                    block.render();
                }
            });
        },
        'set:active': function(pathName){
            var block = this;

            block.$navigation
                .find('.topBar__active')
                .removeClass('topBar__active');
            
            block.$navigation.
                find('[href="' + pathName + '"]')
                .addClass('topBar__active');
        }
    });

    return new TopBar();
});