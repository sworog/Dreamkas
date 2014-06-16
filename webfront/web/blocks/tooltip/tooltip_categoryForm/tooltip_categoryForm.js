define(function(require) {
        //requirements
        var Tooltip = require('blocks/tooltip/tooltip'),
            CategoryModel = require('models/category');

        return Tooltip.extend({
            model: null,
            collection: null,
            template: require('tpl!./template.ejs'),
            listeners: {
                'blocks.form_category': {
                    'submit:success': function() {
                        var block = this;

                        if (block.collection){
                            block.model = new CategoryModel({}, {
                                group: block.models.group
                            });
                            block.render();
                            block._startListening();
                            block.el.querySelector('[type="text"]').focus();
                        } else {
                            block.hide();
                        }
                    }
                }
            },
            blocks: {
                form_category: function(){
                    var block = this,
                        Form = require('kit/form');

                    return new Form({
                        el: block.el.querySelector('form'),
                        model: block.model,
                        collection: block.collection
                    });
                }
            },
            show: function(opt) {
                var block = this;

                Tooltip.prototype.show.apply(this, arguments);

                block._startListening();

                block.el.querySelector('[type="text"]').focus();
            },
            align: function(){
                var block = this,
                    $el = $(block.el),
                    $trigger = $(block.trigger),
                    $container = $(block.container);

                $(block.el)
                    .css({
                        top: $trigger.offset().top + $container.scrollTop() - ($el.outerHeight() - $trigger.outerHeight()) / 2,
                        left: $trigger.offset().left - $container.offset().left
                    })
            }
        });
    }
);