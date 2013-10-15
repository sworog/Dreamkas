define(function(require) {
        //requirements
        var Tooltip_menu = require('blocks/tooltip/tooltip_menu/tooltip_menu'),
            CatalogSubCategoryModel = require('models/catalogSubCategory'),
            Tooltip_catalogSubCategoryForm = require('blocks/tooltip/tooltip_catalogSubCategoryForm/tooltip_catalogSubCategoryForm');

        return Tooltip_menu.extend({
            __name__: 'tooltip_catalogSubCategoryMenu',
            catalogSubCategoryModel: new CatalogSubCategoryModel(),
            events: {
                'click .tooltip__editLink': function(e) {
                    e.preventDefault();
                    var block = this,
                        $target = $(e.target);

                    block.tooltip_catalogSubCategoryForm.show({
                        model: block.catalogSubCategoryModel,
                        collection: null,
                        $trigger: $target
                    });

                    block.hide();
                },
                'click .tooltip__removeLink': function(e) {
                    e.preventDefault();
                    var block = this,
                        $target = $(e.target);

                    if ($target.hasClass('preloader_rows')) {
                        return;
                    }

                    $target.addClass('preloader_rows');
                    block.catalogSubCategoryModel.destroy({
                        complete: function() {
                            $target.removeClass('preloader_rows');
                            block.hide();
                        },
                        error: function(model, response) {
                            alert(LH.translate(response.responseJSON.message));
                        }
                    });
                }
            },
            initialize: function() {
                var block = this;

                block.tooltip_catalogSubCategoryForm = $('[block="tooltip_catalogSubCategoryForm"]').data('tooltip_catalogSubCategoryForm') || new Tooltip_catalogSubCategoryForm({
                    model: block.catalogSubCategoryModel
                });
            },
            remove: function() {
                var block = this;

                block.tooltip_catalogSubCategoryForm.remove();

                Tooltip_menu.prototype.remove.call(block);
            }
        });
    }
);