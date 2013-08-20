define(function(require) {
    var Block = require('kit/block');

    var router = new Backbone.Router();

    return Block.extend({
        __name__: 'page',
        el: document.body,
        events: {
            'click [href]': 'click [href]'
        },
        'click [href]': function(e){
            e.preventDefault();
            router.navigate($(this).attr('href'), {
                trigger: true
            });
        }
    });
});