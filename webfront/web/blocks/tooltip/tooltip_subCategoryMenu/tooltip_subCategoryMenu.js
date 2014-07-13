define(function(require) {
        //requirements
        var Tooltip = require('blocks/tooltip/tooltip');

        return Tooltip.extend({
            template: require('ejs!./template.ejs'),
            categoryId: null,
            groupId: null,
            model: null,
            container: '.localNavigation',
            events: {
                'click .tooltip__editLink': function(e) {
                    e.preventDefault();
                    var block = this;

                    block.blocks.tooltip_subCategoryForm.show({
                        model: block.model,
                        categoryId: block.categoryId,
                        groupId: block.groupId,
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
                tooltip_subCategoryForm: function(){
                    var Tooltip_subCategoryForm = require('blocks/tooltip/tooltip_subCategoryForm/tooltip_subCategoryForm');

                    return new Tooltip_subCategoryForm({
                        container: '.localNavigation'
                    });
                }
            }
        });
    }
);