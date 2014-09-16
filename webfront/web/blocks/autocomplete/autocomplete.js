define(function(require) {
    //requirements
    var Block = require('kit/block/block'),
        config = require('config'),
        cookies = require('cookies');

    require('typeahead');

    return Block.extend({
		value: '',
		resetLink: false,
		template: require('ejs!./template.ejs'),
		events: {
			'change input.form-control': function(e) {
				if ($(e.currentTarget).val() == '')
				{
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
			}
		},
        remoteUrl: null,
        initialize: function() {
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

			PAGE.on('status:loaded', function() {
				block.initEngine();
				block.initTypeahead();
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