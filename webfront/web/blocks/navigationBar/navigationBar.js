define(function(require) {
    //requirements
    var Block = require('kit/core/block.deprecated'),
        LH = require('LH'),
        logout = require('utils/logout'),
        currentUserModel = require('models/currentUser');

    return new (Block.extend({
        __name__: 'navigationBar',
        currentUserModel: currentUserModel,
        userPermissions: null,
        template: require('tpl!./navigationBar.html'),
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

            block.userPermissions = currentUserModel.permissions;

            block.$el.prependTo('body');

            block.render();
        }
    }));
});