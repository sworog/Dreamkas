this.$lh_datepicker= function(obj, currentTime) {

    var jqObj = $jq(obj);

    var options = {
        onClose: $lh_datepicker_onClose
    }

    jqObj.datepicker(options);

    var currentTime = currentTime || false;

    if (currentTime) {
        jqObj.datetimepicker('setDate', new Date())
    }
}