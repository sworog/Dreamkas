define(function(require) {
        //requirements
        var Tooltip = require('blocks/tooltip/tooltip'),
            Tooltip_catalogGroupForm = require('blocks/tooltip/tooltip_groupForm/tooltip_groupForm'),
            CatalogGroupModel = require('models/group');

        return Tooltip.extend({
            template: require('tpl!./template.ejs'),
            model: null,
            events: {
                'click .tooltip__editLink': function(e) {
                    e.preventDefault();
                    var block = this,
                        $target = $(e.target);

                    block.tooltip_catalogGroupForm.show({
                        model: block.catalogGroupModel,
                        collection: null,
                        $trigger: $target
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
                tooltip_groupForm: require('blocks/tooltip/tooltip_groupForm/tooltip_groupForm')
            }
        });
    }
);