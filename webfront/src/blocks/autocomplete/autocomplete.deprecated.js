define(function(require) {
    //requirements
    var Block = require('kit/block/block'),
        config = require('config'),
        checkKey = require('kit/checkKey/checkKey'),
        cookies = require('cookies');

    require('typeahead');

    return Block.extend({
        value: '',
        resetLink: false,
        autofocus: false,
        events: {
            'change input.form-control': function(e) {

                if ($(e.currentTarget).val() == '') {
                    this.trigger('input:clear');
                }
            },
            'click .autocomplete__resetButton': function(e) {
                e.preventDefault();

                var block = this;

                block.$el.find('input.form-control')
                    .typeahead('val', '')
                    .focus();

                this.trigger('input:clear');
            },
            'keyup input.form-control': function(e) {

                if (checkKey(e.keyCode, ['UP', 'DOWN', 'LEFT', 'RIGHT', 'TAB'])) {
                    e.stopPropagation();
                }

            }
        },
        remoteUrl: null,
        render: function() {
            var block = this;

            Block.prototype.render.apply(block, arguments);

            block.initEngine();
            block.initTypeahead();

            block.$el.on('typeahead:selected', function(e, item) {
                block.$el.find('input.form-control').blur();
            });
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
        initTypeahead: function() {
            var block = this;

            block.$el.find('input').typeahead({
                    highlight: true,
                    minLength: 3
                },
                {
                    source: block.engine.ttAdapter()
                });
        },
        remove: function() {
            var block = this;

            block.$el.find('input.form-control').typeahead('destroy');

            Block.prototype.remove.apply(block, arguments);
        }
    });
});