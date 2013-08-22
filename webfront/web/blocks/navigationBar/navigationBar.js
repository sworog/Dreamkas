define(function(require) {
    //requirements
    var Block = require('kit/block'),
        logout = require('utils/logout'),
        currentUserModel = require('models/currentUser');

    return new (Block.extend({
        __name__: 'navigationBar',
        currentUserModel: currentUserModel,
        userPermissions: null,
        templates: {
            index: require('tpl!blocks/navigationBar/templates/index.html')
        },
        events: {
            'click .navigationBar__logoutLink': 'click .navigationBar__logoutLink'
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
        'click .navigationBar__logoutLink': function(e) {
            e.preventDefault();
            logout();
        },
        initialize: function() {
            var block = this;

            block.userPermissions = currentUserModel.permissions;

            block.$el.prependTo('body');

            block.render();
        },
        'set:active': function(pathName) {
            var block = this;

            block.$navigation
                .find('.navigationBar__active')
                .removeClass('navigationBar__active');

            block.$navigation.
                find('[href="' + pathName + '"]')
                .addClass('navigationBar__active');
        }
    }));
});