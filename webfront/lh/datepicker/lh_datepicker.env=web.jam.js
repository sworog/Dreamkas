this.$lh_datepicker= function(obj, currentTime) {

    var jqObj = $jq(obj);

    jqObj.mask('99.99.9999');

    var options = {
        onClose: $lh_datepicker_onClose
    }

    jqObj.datepicker(options);

    var currentTime = currentTime || false;

    if (currentTime) {
        jqObj.datepicker('setDate', new Date())
    }
}