define(function(require) {
    //requirements
    var Block = require('kit/block'),
        currentPage = require('kit/pages/currentPage');

    var router = new Backbone.Router();

    return Block.extend({
        el: document.body,
        permissions: {},
        constructor: function() {
            var page = this;
            
            page._configure();
            page._ensureElement();

            if (page.accessDenied) {
                router.navigate('/403', {
                    trigger: true
                });
            } else {
                page.initialize.apply(page, arguments);
            }
        },
        _configure: function() {

            Block.prototype._configure.apply(this, arguments);

            var page = this;

            page.accessDenied = _.some(page.permissions, function(value, key) {
                return !LH.isAllow(key, value);
            });

            page.cid = _.uniqueId('page');

            if (currentPage) {
                page.referer = _.clone(currentPage);
                currentPage.stopListening();
            }

            currentPage = page;
        },
        _ensureElement: function() {

            Backbone.View.prototype._ensureElement.apply(this, arguments);

            var page = this;

            page.$el
                .removeAttr('class')
                .addClass('page ' + page.__name__);
            
            page.set('loading', true);
        },
        initialize: function() {
            var page = this;
            page.render();
        },
        render: function() {
            var page = this;

            _.each(page.templates, function(template, selector) {
                var $renderContainer = $(selector);

                page.removeBlocks($renderContainer);

                $renderContainer.html(template(page));
            });

            page.set('loading', false);
        },
        removeBlocks: function($container) {
            var blocks = [];

            $container.find('[block]').each(function() {
                var $block = $(this),
                    __name__ = $block.attr('block');

                blocks.push($block.data(__name__));
            });

            _.each(blocks, function(block) {
                block.remove();
            });
        }
    });
});