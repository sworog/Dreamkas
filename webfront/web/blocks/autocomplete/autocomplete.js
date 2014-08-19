define(function(require) {
    //requirements
    var Block = require('kit/block/block'),
        config = require('config'),
        cookies = require('cookies');

    require('typehead');

    return Block.extend({
        el: '.autocomplete',
        remoteUrl: null,
        initialize: function() {
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            block.initEngine();
            block.initTypehead();
        },
        initEngine: function() {
            var block = this;

            block.engine = new Bloodhound({
                remote: config.baseApiUrl + block.remoteUrl,
                ajax: {
                    dataType: 'json',
                    headers: {
                        Authorization: 'Bearer ' + cookies.get('token')
                    }
                },
                datumTokenizer: function(d) {
                    return Bloodhound.tokenizers.whitespace(d.val);
                },
                queryTokenizer: Bloodhound.tokenizers.whitespace
            });

            block.engine.initialize();

        },
        initTypehead: function() {
            var block = this;

            block.$el.typeahead({
                    highlight: true,
                    minLength: 3
                },
                {
                    source: block.engine.ttAdapter()
                });

        },
        remove: function() {
            var block = this;

            block.$el.typehead('destroy');

            Block.prototype.remove.apply(block, arguments);
        }
    });
});