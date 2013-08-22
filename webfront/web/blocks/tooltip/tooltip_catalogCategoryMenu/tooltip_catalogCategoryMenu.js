define(function(require) {
        //requirements
        var Tooltip_menu = require('blocks/tooltip/tooltip_menu/tooltip_menu'),
            CatalogCategoryModel = require('models/catalogCategory'),
            Tooltip_catalogCategoryForm = require('blocks/tooltip/tooltip_catalogCategoryForm/tooltip_catalogCategoryForm');

        return Tooltip_menu.extend({
            __name__: 'tooltip_catalogCategoryMenu',
            catalogCategoryModel: new CatalogCategoryModel(),
            events: {
                'click .tooltip__editLink': 'click .tooltip__editLink',
                'click .tooltip__removeLink': 'click .tooltip__removeLink'
            },
            'click .tooltip__editLink': function(e) {
                e.preventDefault();
                var block = this,
                    $target = $(e.target);

                block.tooltip_catalogCategoryForm.show({
                    model: block.catalogCategoryModel,
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

                block.catalogCategoryModel.destroy({
                    complete: function(){
                        $target.removeClass('preloader_rows');
                        block.hide();
                    },
                    error: function(model, response) {
                        alert(LH.text(response.responseJSON.message));
                    }
                });
            },
            initialize: function() {
                var block = this;

                block.tooltip_catalogCategoryForm = $('[block="tooltip_catalogCategoryForm"]').data('tooltip_catalogCategoryForm') || new Tooltip_catalogCategoryForm({
                    model: block.catalogCategoryModel
                });
            },
            remove: function() {
                var block = this;

                block.tooltip_catalogCategoryForm.remove();

                Tooltip_menu.prototype.remove.call(block);
            }
        });
    }
);