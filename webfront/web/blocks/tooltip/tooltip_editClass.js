define(
    [
        '/kit/tooltip/tooltip.js',
        '/kit/form/form.js'
    ],
    function(Tooltip, Form) {
        return Tooltip.extend({
            classModel: null,

            initialize: function(){
                var block = this;

                Tooltip.prototype.initialize.call(this);

                block.form = new Form({
                    model: block.classModel
                });
            }
        });
    }
);