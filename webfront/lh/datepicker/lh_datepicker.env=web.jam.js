this.$lh_datepicker= function(obj, currentTime) {
    var obj = $jq(obj);
    var options = {
        controlType: $lh_datepicker_control
    }
    obj.datetimepicker(options)
    var currentTime = currentTime || false;
    if (currentTime) {
        // set current datetime
        obj.datetimepicker('setDate', new Date())
    }
}