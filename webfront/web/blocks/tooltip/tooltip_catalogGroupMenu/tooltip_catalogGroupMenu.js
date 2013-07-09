define(function(require) {
        //requirements
        var Tooltip_menu = require('blocks/tooltip/tooltip_menu/tooltip_menu'),
            Tooltip_catalogGroupForm = require('blocks/tooltip/tooltip_catalogGroupForm/tooltip_catalogGroupForm');

        return Tooltip_menu.extend({
            catalogGroupModel: null,
            blockName: 'tooltip_catalogGroupMenu',
            events: {
                'click .tooltip__editLink': function(e) {
                    e.preventDefault();
                    var block = this,
                        $target = $(e.target);

                    block.tooltip_catalogGroupForm.show({
                        catalogGroupModel: block.catalogGroupModel,
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

                    if (block.catalogGroupModel.categories && block.catalogGroupModel.categories.length) {
                        alert('Необходимо удалить все категории из группы');
                        block.hide();
                    } else {
                        $target.addClass('preloader_rows');
                        block.catalogGroupModel.destroy({
                            success: function() {
                                $target.removeClass('preloader_rows');
                                block.hide();
                            }
                        });
                    }
                }
            },
            initialize: function() {
                var block = this;

                Tooltip_menu.prototype.initialize.call(this);

                block.tooltip_catalogGroupForm = $('[block="tooltip_catalogGroupForm"]').data('tooltip_catalogGroupForm') || new Tooltip_catalogGroupForm({
                    $trigger: block.$trigger,
                    catalogGroupModel: block.catalogGroupModel
                });
            }
        });
    }
);