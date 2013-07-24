define(function(require) {
        //requirements
        var Tooltip_menu = require('blocks/tooltip/tooltip_menu/tooltip_menu'),
            Tooltip_catalogGroupForm = require('blocks/tooltip/tooltip_catalogGroupForm/tooltip_catalogGroupForm'),
            CatalogGroupModel = require('models/catalogGroup');

        return Tooltip_menu.extend({
            __name__: 'tooltip_catalogGroupMenu',
            catalogGroupModel: new CatalogGroupModel(),
            events: {
                'click .tooltip__editLink': 'click .tooltip__editLink',
                'click .tooltip__removeLink': 'click .tooltip__removeLink'
            },
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
                var block = this,
                    $target = $(e.target);

                if ($target.hasClass('preloader_rows')) {
                    return;
                }

                $target.addClass('preloader_rows');

                block.catalogGroupModel.destroy({
                    complete: function() {
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

                Tooltip_menu.prototype.initialize.call(this);

                block.tooltip_catalogGroupForm = $('[block="tooltip_catalogGroupForm"]').data('tooltip_catalogGroupForm') || new Tooltip_catalogGroupForm({
                    $trigger: block.$trigger,
                    model: block.catalogGroupModel
                });
            },
            remove: function() {
                var block = this;

                block.tooltip_catalogGroupForm.remove();

                Tooltip_menu.prototype.remove.call(block);
            }
        });
    }
);