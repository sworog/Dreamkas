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
        userPermissions: userPermissions,
        templates: {
            index: require('tpl!blocks/topBar/templates/index.html')
        },
        events: {
            'click .topBar__logoutLink': function(e) {
                e.preventDefault();
                app.logout();
            }
        },
        listeners: {
            currentUserModel: {
                change: function() {
                    var block = this;
                    block.render();
                }
            },
            userPermissions: {
                change: function() {
                    var block = this;
                    block.render();
                }
            }
        },
        initialize: function() {
            var block = this;

            block.$el.prependTo('body');

            Block.prototype.initialize.call(this);
        },
        'set:active': function(pathName) {
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