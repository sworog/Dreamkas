define(function(require) {
    //requirements
    var Block = require('kit/block'),
        logout = require('utils/logout'),
        currentUserModel = require('models/currentUser');

    var TopBar = Block.extend({
        __name__: 'topBar',
        currentUserModel: currentUserModel,
        userPermissions: null,
        templates: {
            index: require('tpl!blocks/topBar/templates/index.html')
        },
        events: {
            'click .topBar__logoutLink': 'click .topBar__logoutLink'
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
        'click .topBar__logoutLink': function(e) {
            e.preventDefault();
            logout();
        },
        initialize: function() {
            var block = this;

            block.userPermissions = currentUserModel.permissions;

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