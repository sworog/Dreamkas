define(
    [
        './tooltip_editMenu.js',
        '/models/catalogGroup.js'
    ],
    function(tooltip_editMenu, CatalogGroupModel) {
        return tooltip_editMenu.extend({
            groupId: null,
            events: {
                'click .catalogEditTooltip__editLink': function(e){
                    e.preventDefault();
                    var block = this;


                },
                'click .tooltip__removeLink': function(e){
                    e.preventDefault();
                    var block = this,
                        groups = block.classModel.get('groups'),
                        groupModel = new CatalogGroupModel({
                            id: block.groupId
                        });

                    groupModel.destroy({
                        success: function(){
                            block.classModel.set('groups', _.reject(groups, function(group){
                                return group.id === block.groupId
                            }));
                        }
                    });

                    block.hide();
                }
            }
        });
    }
);