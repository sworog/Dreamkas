define(function(require) {
    //requirements
    var Block = require('kit/block'),
        UserModel = require('models/user');

    return Block.extend({
        blockName: 'user',
        className: 'user',
        userModel: null,
        userId: null,
        templates: {
            index: require('tpl!./templates/user.html')
        },
        listeners: {
            userModel: {
                change: function(){
                    var block = this;

                    block.render();
                }
            }
        },
        initialize: function(){
            var block = this;

            block.userModel = new UserModel({
                id: block.userId
            });

            block.userModel.fetch();
        }
    });
});