define(function(require) {
        //requirements
        var Tooltip = require('blocks/tooltip/tooltip');

        return Tooltip.extend({
            template: require('ejs!./template.ejs'),
            model: null,
            events: {
                'click .tooltip__editLink': function(e) {
                    e.preventDefault();
                    var block = this;

                    block.blocks.tooltip_categoryForm.show({
                        model: block.model,
                        trigger: e.target
                    });

                    block.hide();
                },
                'click .tooltip__removeLink': function(e) {
                    e.preventDefault();

                    var block = this;

                    if (e.target.classList.contains('preloader_stripes')) {
                        return;
                    }

                    e.target.classList.add('preloader_stripes');

                    block.model.destroy({
                        complete: function() {
                            e.target.classList.remove('preloader_stripes');
                            block.hide();
                        },
                        error: function(model, response) {
                            alert(block.getText(response.responseJSON.message));
                        }
                    });
                }
            },
            blocks: {
                tooltip_categoryForm: require('blocks/tooltip/tooltip_categoryForm/tooltip_categoryForm')
            }
        });
    }
);