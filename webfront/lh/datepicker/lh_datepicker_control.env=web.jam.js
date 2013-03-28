this.$lh_datepicker_control=  {
    create: function(tp_inst, obj, unit, val, min, max, step){
        var input = jQuery('<input class="ui-timepicker-input" value="'+val+'" style="width:50%">');
        input.change(function(e){
            if (e.originalEvent !== undefined) {
                tp_inst._onTimeChange();
            }
            tp_inst._onSelectHandler();
        })
        input.appendTo(obj);
        return obj;
    },
    options: function(tp_inst, obj, unit, opts, val){
        /*
        if (typeof(opts) == 'string' && val !== undefined) {
            return obj.find('.ui-timepicker-input').spinner(opts, val);
        }
        return obj.find('.ui-timepicker-input').spinner(opts);
        */
    },
    value: function(tp_inst, obj, unit, val){
        if (val !== undefined) {
            return obj.find('.ui-timepicker-input').val(val);
        } else {
            return obj.find('.ui-timepicker-input').val();
        }
    }
};